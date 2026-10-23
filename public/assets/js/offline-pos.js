(function () {
    'use strict';

    var root = document.getElementById('offline-pos');
    if (!root || !window.PosQueue) return;

    var Q = window.PosQueue;
    var currency = root.dataset.currency || '';
    var catalog = null;
    var cart = []; // {id, name, price, qty}

    function $(id) { return document.getElementById(id); }
    function money(n) { return currency + ' ' + (Math.round(n * 100) / 100).toFixed(2); }
    function el(tag, cls, text) {
        var e = document.createElement(tag);
        if (cls) e.className = cls;
        if (text !== undefined) e.textContent = text;
        return e;
    }

    function alertBox(msg, type) {
        var a = $('op-alert');
        a.className = 'alert alert-' + (type || 'info');
        a.textContent = msg;
        clearTimeout(alertBox.t);
        alertBox.t = setTimeout(function () { a.className = 'alert d-none'; }, 6000);
    }

    function productById(id) {
        return catalog && catalog.products.filter(function (p) { return p.id === id; })[0];
    }

    function updateNet() {
        var n = $('op-net');
        n.textContent = navigator.onLine ? 'Online' : 'Offline';
        n.className = 'badge rounded-pill ' + (navigator.onLine ? 'bg-success' : 'bg-secondary');
    }

    /* Catalog */
    function pendingQuantities() {
        return Q.list().then(function (rows) {
            var q = {};
            rows.filter(function (r) { return r.status !== 'failed'; }).forEach(function (r) {
                r.items.forEach(function (i) { q[i.product_id] = (q[i.product_id] || 0) + i.quantity; });
            });
            return q;
        });
    }

    function loadCatalog(forceRefresh) {
        var online = navigator.onLine;
        var chain = Promise.resolve();
        if (online && forceRefresh !== false) {
            chain = Q.sync().then(function () { return Q.refreshCatalog(); }).then(function (fresh) {
                // Stock for sales still waiting to sync has not been deducted on the server yet.
                return pendingQuantities().then(function (q) {
                    fresh.products.forEach(function (p) { p.stock = Math.max(0, p.stock - (q[p.id] || 0)); });
                    return Q.saveCatalog(fresh);
                });
            }).catch(function () { /* fall back to the saved copy */ });
        }
        return chain.then(function () { return Q.getCatalog(); }).then(function (c) {
            catalog = c && c.user_id === (window.POS_QUEUE || {}).userId ? c : null;
            renderCatalogInfo();
            renderCustomers();
            renderProducts();
        });
    }

    function renderCatalogInfo() {
        var info = $('op-catalog-info');
        if (!catalog) {
            info.textContent = navigator.onLine
                ? 'No product catalog saved yet.'
                : 'No product catalog saved on this device. Connect to the internet once and open this page to download it.';
            return;
        }
        info.textContent = catalog.products.length + ' products saved. Last updated ' +
            new Date(catalog.generated_at).toLocaleString() + '.';
    }

    function renderCustomers() {
        var sel = $('op-customer');
        var keep = sel.value;
        sel.length = 1;
        (catalog ? catalog.customers : []).forEach(function (c) {
            var o = el('option', null, c.name + (c.phone ? ' (' + c.phone + ')' : ''));
            o.value = c.id;
            sel.appendChild(o);
        });
        sel.value = keep;
    }

    /* Products */
    function renderProducts() {
        var list = $('op-products');
        list.textContent = '';
        if (!catalog) {
            list.appendChild(el('li', 'list-group-item text-600', 'Product catalog unavailable.'));
            return;
        }
        var term = $('op-search').value.trim().toLowerCase();
        var rows = catalog.products.filter(function (p) {
            return !term || p.name.toLowerCase().indexOf(term) > -1 ||
                (p.sku || '').toLowerCase().indexOf(term) > -1 || (p.barcode || '').toLowerCase().indexOf(term) > -1;
        }).slice(0, 40);

        if (!rows.length) {
            list.appendChild(el('li', 'list-group-item text-600', 'No matching products in stock.'));
            return;
        }
        rows.forEach(function (p) {
            var li = el('li', 'list-group-item list-group-item-action d-flex justify-content-between align-items-center');
            li.style.cursor = 'pointer';
            li.tabIndex = 0;
            var left = el('div');
            left.appendChild(el('div', 'fw-semi-bold', p.name));
            left.appendChild(el('div', 'fs--1 text-600', p.stock + ' in stock'));
            li.appendChild(left);
            li.appendChild(el('span', 'fw-bold', money(p.price)));
            li.addEventListener('click', function () { addToCart(p.id); });
            li.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); addToCart(p.id); } });
            list.appendChild(li);
        });
    }

    /* Cart */
    function addToCart(id, qty) {
        var p = productById(id);
        if (!p) return;
        var line = cart.filter(function (l) { return l.id === id; })[0];
        var next = (line ? line.qty : 0) + (qty || 1);
        if (next > p.stock) {
            alertBox('Only ' + p.stock + ' of ' + p.name + ' in stock.', 'warning');
            return;
        }
        if (line) line.qty = next;
        else cart.push({ id: id, name: p.name, price: p.price, qty: next });
        renderCart();
    }

    function setQty(id, qty) {
        var p = productById(id);
        var line = cart.filter(function (l) { return l.id === id; })[0];
        if (!line || !p) return;
        qty = Math.floor(Number(qty));
        if (!qty || qty < 1) qty = 1;
        if (qty > p.stock) { qty = p.stock; alertBox('Only ' + p.stock + ' of ' + p.name + ' in stock.', 'warning'); }
        line.qty = qty;
        renderCart();
    }

    function renderCart() {
        var body = $('op-cart');
        body.textContent = '';
        var total = 0;
        cart.forEach(function (l) {
            total += l.price * l.qty;
            var tr = el('tr');
            tr.appendChild(el('td', null, l.name));

            var qtyTd = el('td');
            var input = el('input', 'form-control form-control-sm');
            input.type = 'number'; input.min = 1; input.value = l.qty; input.setAttribute('aria-label', 'Quantity for ' + l.name);
            input.addEventListener('change', function () { setQty(l.id, input.value); });
            qtyTd.appendChild(input);
            tr.appendChild(qtyTd);

            tr.appendChild(el('td', 'text-end', money(l.price * l.qty)));

            var rm = el('td', 'text-end');
            var b = el('button', 'btn btn-link text-danger btn-sm p-0', '✕');
            b.type = 'button'; b.setAttribute('aria-label', 'Remove ' + l.name);
            b.addEventListener('click', function () { cart = cart.filter(function (x) { return x.id !== l.id; }); renderCart(); });
            rm.appendChild(b);
            tr.appendChild(rm);
            body.appendChild(tr);
        });
        $('op-cart-table').classList.toggle('d-none', !cart.length);
        $('op-cart-empty').classList.toggle('d-none', !!cart.length);
        $('op-total').textContent = money(total);
    }

    function saveSale() {
        if (!catalog) return alertBox('Download the product catalog first (go online once).', 'warning');
        if (!cart.length) return alertBox('The cart is empty.', 'warning');
        var payment = $('op-payment').value;
        var customer = $('op-customer').value;
        if (payment === 'credit' && !customer) return alertBox('Select a customer for credit sales.', 'warning');

        var total = cart.reduce(function (s, l) { return s + l.price * l.qty; }, 0);
        var sale = {
            store_id: catalog.store_id,
            customer_id: customer ? parseInt(customer, 10) : null,
            payment_method: payment,
            items: cart.map(function (l) { return { product_id: l.id, quantity: l.qty }; }),
            display: cart.map(function (l) { return l.qty + ' × ' + l.name; }).join(', '),
            est_total: total
        };

        cart.forEach(function (l) { productById(l.id).stock -= l.qty; });

        Q.add(sale).then(function () { return Q.saveCatalog(catalog); }).then(function () {
            cart = [];
            $('op-customer').value = '';
            renderCart();
            renderProducts();
            alertBox(navigator.onLine ? 'Sale saved. Syncing…' : 'Sale saved on this device. It will sync when you are online.', 'success');
            if (navigator.onLine) Q.sync();
        }).catch(function () {
            alertBox('Could not save the sale on this device.', 'danger');
        });
    }

    /* Queue */
    function renderQueue() {
        Q.list().then(function (rows) {
            var list = $('op-queue');
            list.textContent = '';
            $('op-queue-empty').classList.toggle('d-none', !!rows.length);

            var waiting = rows.filter(function (r) { return r.status !== 'failed'; }).length;
            var failed = rows.length - waiting;
            $('op-pending').textContent = waiting + ' waiting to sync' + (failed ? ', ' + failed + ' need attention' : '');
            $('op-pending').className = 'badge rounded-pill ' + (failed ? 'bg-danger' : (waiting ? 'bg-warning text-dark' : 'bg-success'));

            rows.forEach(function (r) {
                var li = el('li', 'list-group-item');
                var top = el('div', 'd-flex justify-content-between');
                top.appendChild(el('span', 'fw-semi-bold', new Date(r.sold_at).toLocaleString()));
                top.appendChild(el('span', 'fw-bold', money(r.est_total || 0)));
                li.appendChild(top);
                li.appendChild(el('div', 'fs--1 text-600', r.display || ''));

                if (r.status === 'failed') {
                    li.appendChild(el('div', 'fs--1 text-danger mt-1', r.message || 'Rejected by server'));
                    var actions = el('div', 'mt-1');
                    var retry = el('button', 'btn btn-falcon-default btn-sm me-2', 'Retry');
                    retry.type = 'button';
                    retry.addEventListener('click', function () { Q.retry(r.uuid).then(Q.sync); });
                    var drop = el('button', 'btn btn-falcon-danger btn-sm', 'Discard');
                    drop.type = 'button';
                    drop.addEventListener('click', function () {
                        if (confirm('Discard this sale? It will not be recorded.')) Q.remove(r.uuid);
                    });
                    actions.appendChild(retry);
                    actions.appendChild(drop);
                    li.appendChild(actions);
                } else {
                    li.appendChild(el('div', 'fs--1 text-warning mt-1', 'Waiting to sync'));
                }
                list.appendChild(li);
            });
        });
    }

    /* Wiring */
    $('op-search').addEventListener('input', renderProducts);
    $('op-search').addEventListener('keydown', function (e) {
        if (e.key !== 'Enter' || !catalog) return;
        var code = this.value.trim().toLowerCase();
        var hit = code && catalog.products.filter(function (p) {
            return (p.barcode || '').toLowerCase() === code || (p.sku || '').toLowerCase() === code;
        })[0];
        if (hit) { addToCart(hit.id); this.value = ''; renderProducts(); }
    });
    $('op-save').addEventListener('click', saveSale);
    $('op-clear').addEventListener('click', function () { cart = []; renderCart(); });
    $('op-sync').addEventListener('click', function () {
        if (!navigator.onLine) return alertBox('You are offline.', 'warning');
        Q.sync().then(function () { return loadCatalog(true); });
    });
    $('op-refresh').addEventListener('click', function () {
        if (!navigator.onLine) return alertBox('You are offline.', 'warning');
        loadCatalog(true).then(function () { alertBox('Products updated.', 'success'); });
    });

    window.addEventListener('online', function () { updateNet(); });
    window.addEventListener('offline', updateNet);
    window.addEventListener('posqueue:changed', renderQueue);
    window.addEventListener('posqueue:synced', function (e) {
        var s = e.detail;
        alertBox(s.synced + ' sale(s) synced' + (s.failed ? ', ' + s.failed + ' need attention.' : '.'), s.failed ? 'warning' : 'success');
        if (navigator.onLine) loadCatalog(true);
    });

    updateNet();
    renderCart();
    renderQueue();
    loadCatalog(true);
})();
