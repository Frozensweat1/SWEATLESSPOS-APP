(function () {
    'use strict';

    var cfg = window.POS_QUEUE || {};
    var DB_NAME = 'sweatless-pos';
    var dbPromise;
    var syncing = false;

    function open() {
        if (dbPromise) return dbPromise;
        dbPromise = new Promise(function (resolve, reject) {
            if (!window.indexedDB) return reject(new Error('IndexedDB unavailable'));
            var req = indexedDB.open(DB_NAME, 1);
            req.onupgradeneeded = function () {
                var db = req.result;
                db.createObjectStore('queue', { keyPath: 'uuid' });
                db.createObjectStore('meta', { keyPath: 'key' });
            };
            req.onsuccess = function () { resolve(req.result); };
            req.onerror = function () { reject(req.error); };
        });
        return dbPromise;
    }

    function tx(store, mode, fn) {
        return open().then(function (db) {
            return new Promise(function (resolve, reject) {
                var t = db.transaction(store, mode);
                var result = fn(t.objectStore(store));
                t.oncomplete = function () { resolve(result && 'result' in result ? result.result : undefined); };
                t.onerror = t.onabort = function () { reject(t.error); };
            });
        });
    }

    function changed() {
        window.dispatchEvent(new CustomEvent('posqueue:changed'));
    }

    function uuid() {
        if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            var r = Math.random() * 16 | 0;
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    var Q = {
        uuid: uuid,

        add: function (sale) {
            sale.uuid = sale.uuid || uuid();
            sale.status = 'pending';
            sale.user_id = cfg.userId;
            sale.sold_at = sale.sold_at || new Date().toISOString();
            return tx('queue', 'readwrite', function (s) { s.put(sale); }).then(function () { changed(); return sale; });
        },

        list: function () {
            return tx('queue', 'readonly', function (s) { return s.getAll(); }).then(function (rows) {
                return (rows || []).filter(function (r) { return r.user_id === cfg.userId; })
                    .sort(function (a, b) { return a.sold_at < b.sold_at ? -1 : 1; });
            });
        },

        remove: function (id) {
            return tx('queue', 'readwrite', function (s) { s.delete(id); }).then(changed);
        },

        retry: function (id) {
            return tx('queue', 'readwrite', function (s) {
                var g = s.get(id);
                g.onsuccess = function () {
                    if (g.result) { g.result.status = 'pending'; delete g.result.message; s.put(g.result); }
                };
            }).then(changed);
        },

        saveCatalog: function (data) {
            return tx('meta', 'readwrite', function (s) { s.put({ key: 'catalog', value: data }); });
        },

        getCatalog: function () {
            return tx('meta', 'readonly', function (s) { return s.get('catalog'); }).then(function (r) { return r && r.value; });
        },

        clearCatalog: function () {
            return tx('meta', 'readwrite', function (s) { s.delete('catalog'); });
        },

        refreshCatalog: function () {
            return fetch(cfg.catalog, { credentials: 'same-origin', headers: { Accept: 'application/json' }, cache: 'no-store' })
                .then(function (res) {
                    if (!res.ok) throw new Error('catalog ' + res.status);
                    return res.json();
                })
                .then(function (data) {
                    data.user_id = cfg.userId;
                    return Q.saveCatalog(data).then(function () { return data; });
                });
        },

        // Pushes pending sales; failed ones stay until the cashier retries or discards them.
        sync: function () {
            if (syncing || !navigator.onLine || !cfg.sync) return Promise.resolve(null);
            syncing = true;

            return Q.list().then(function (rows) {
                var pending = rows.filter(function (r) { return r.status === 'pending'; }).slice(0, 50);
                if (!pending.length) return { synced: 0, failed: 0 };

                return fetch(cfg.token, { credentials: 'same-origin', headers: { Accept: 'application/json' }, cache: 'no-store' })
                    .then(function (res) {
                        if (!res.ok) throw new Error('auth');
                        return res.json();
                    })
                    .then(function (t) {
                        return fetch(cfg.sync, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': t.token,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                sales: pending.map(function (r) {
                                    return {
                                        uuid: r.uuid, store_id: r.store_id, items: r.items,
                                        customer_id: r.customer_id || null,
                                        payment_method: r.payment_method, sold_at: r.sold_at
                                    };
                                })
                            })
                        });
                    })
                    .then(function (res) {
                        if (!res.ok) throw new Error('sync ' + res.status);
                        return res.json();
                    })
                    .then(function (body) {
                        var synced = 0, failed = 0;
                        var byId = {};
                        pending.forEach(function (r) { byId[r.uuid] = r; });

                        return tx('queue', 'readwrite', function (s) {
                            body.results.forEach(function (r) {
                                if (r.status === 'synced') { s.delete(r.uuid); synced++; }
                                else if (byId[r.uuid]) {
                                    byId[r.uuid].status = 'failed';
                                    byId[r.uuid].message = r.message || 'Rejected by server';
                                    s.put(byId[r.uuid]);
                                    failed++;
                                }
                            });
                        }).then(function () { return { synced: synced, failed: failed }; });
                    });
            }).then(function (summary) {
                changed();
                if (summary && (summary.synced || summary.failed)) {
                    window.dispatchEvent(new CustomEvent('posqueue:synced', { detail: summary }));
                }
                return summary;
            }).catch(function () {
                // Offline, logged out or server error: leave everything queued for the next attempt.
                return null;
            }).then(function (r) { syncing = false; return r; });
        }
    };

    window.PosQueue = Q;

    window.addEventListener('online', function () { setTimeout(Q.sync, 800); });
    window.addEventListener('load', function () { if (navigator.onLine) setTimeout(Q.sync, 1500); });
    setInterval(function () { if (navigator.onLine) Q.sync(); }, 60000);
})();
