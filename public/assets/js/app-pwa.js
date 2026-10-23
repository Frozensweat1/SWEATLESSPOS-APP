(function () {
    'use strict';

    var cfg = window.SITE_PWA || {};
    var banner, hideTimer, deferred;

    function showBanner(text, withRefresh, autoHideMs) {
        if (!banner) {
            banner = document.createElement('div');
            banner.setAttribute('role', 'status');
            banner.setAttribute('aria-live', 'polite');
            banner.style.cssText = 'position:fixed;left:50%;bottom:1rem;transform:translateX(-50%);z-index:2000;' +
                'padding:.6rem 1rem;border-radius:.5rem;background:#1f2937;color:#fff;font-size:.875rem;' +
                'box-shadow:0 6px 20px rgba(0,0,0,.25);display:none;align-items:center;gap:.75rem';
            document.body.appendChild(banner);
        }
        clearTimeout(hideTimer);
        banner.textContent = text;
        if (withRefresh) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'btn btn-sm btn-light';
            b.textContent = 'Refresh';
            b.addEventListener('click', function () { location.reload(); });
            banner.appendChild(b);
        }
        banner.style.display = 'flex';
        if (autoHideMs) hideTimer = setTimeout(function () { banner.style.display = 'none'; }, autoHideMs);
    }

    function networkState() {
        var offline = !navigator.onLine;
        document.body.classList.toggle('is-offline', offline);
        if (offline) {
            showBanner("You're offline. Showing the last saved dashboard; sales and changes need a connection.");
        } else if (banner && banner.style.display === 'flex') {
            showBanner('Back online.', true, 8000);
        }
    }

    window.addEventListener('online', networkState);
    window.addEventListener('offline', networkState);
    if (!navigator.onLine) networkState();

    // Live updates and form posts can't work offline; stop them before Livewire sends a doomed request.
    document.addEventListener('submit', function (e) {
        if (!navigator.onLine) {
            e.preventDefault();
            e.stopImmediatePropagation();
            showBanner('You need an internet connection to save changes.', false, 4000);
        }
    }, true);

    // Drop cached authenticated pages on logout so the next user of this device can't see them.
    document.addEventListener('click', function (e) {
        var a = e.target.closest && e.target.closest('a[href$="/logout"]');
        if (a && window.caches) {
            caches.keys().then(function (keys) {
                keys.filter(function (k) { return k.indexOf('sweatless-app-') === 0; })
                    .forEach(function (k) { caches.delete(k); });
            });
            if (window.PosQueue) PosQueue.clearCatalog();
        }
    }, true);

    var secure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    if ('serviceWorker' in navigator && secure && cfg.sw) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register(cfg.sw, { scope: cfg.scope }).catch(function () {});
        });
    }

    function toggleInstall(show) {
        document.querySelectorAll('.install-btn').forEach(function (b) { b.hidden = !show; });
    }

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferred = e;
        toggleInstall(true);
    });
    document.addEventListener('click', function (e) {
        if (!deferred || !(e.target.closest && e.target.closest('.install-btn'))) return;
        deferred.prompt();
        deferred.userChoice.finally(function () { deferred = null; toggleInstall(false); });
    });
    window.addEventListener('appinstalled', function () { toggleInstall(false); });
})();
