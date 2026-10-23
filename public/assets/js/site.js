(function () {
    'use strict';

    var cfg = window.SITE_PWA || {};
    var banner, hideTimer;

    function ensureBanner() {
        if (banner) return banner;
        banner = document.createElement('div');
        banner.className = 'net-banner';
        banner.setAttribute('role', 'status');
        banner.setAttribute('aria-live', 'polite');
        document.body.appendChild(banner);
        return banner;
    }

    function showBanner(text, actionLabel, autoHideMs) {
        var el = ensureBanner();
        clearTimeout(hideTimer);
        el.textContent = text;
        if (actionLabel) {
            var b = document.createElement('button');
            b.type = 'button';
            b.textContent = actionLabel;
            b.addEventListener('click', function () { window.location.reload(); });
            el.appendChild(b);
        }
        el.classList.add('is-visible');
        if (autoHideMs) hideTimer = setTimeout(function () { el.classList.remove('is-visible'); }, autoHideMs);
    }

    function applyNetworkState() {
        var offline = !navigator.onLine;
        document.body.classList.toggle('is-offline', offline);
        if (offline) {
            showBanner("You're offline. Showing saved content.");
        } else if (banner && banner.classList.contains('is-visible')) {
            showBanner('Back online.', 'Refresh', 8000);
        }
    }

    window.addEventListener('online', applyNetworkState);
    window.addEventListener('offline', applyNetworkState);
    document.addEventListener('DOMContentLoaded', function () {
        if (!navigator.onLine) applyNetworkState();
    });

    // Block form submissions that need the server while offline (capture phase runs before Livewire).
    document.addEventListener('submit', function (e) {
        if (!navigator.onLine && e.target.closest && e.target.closest('[data-requires-online]')) {
            e.preventDefault();
            e.stopImmediatePropagation();
            showBanner('You need an internet connection to send this form.', null, 4000);
        }
    }, true);

    // Service worker
    var secure = location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1';
    if ('serviceWorker' in navigator && secure && cfg.sw) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register(cfg.sw, { scope: cfg.scope }).catch(function () {});
        });
    }

    // Install prompt
    var deferred;
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferred = e;
        document.querySelectorAll('.install-btn').forEach(function (b) { b.hidden = false; });
    });
    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('.install-btn');
        if (!btn || !deferred) return;
        deferred.prompt();
        deferred.userChoice.finally(function () {
            deferred = null;
            document.querySelectorAll('.install-btn').forEach(function (b) { b.hidden = true; });
        });
    });
    window.addEventListener('appinstalled', function () {
        document.querySelectorAll('.install-btn').forEach(function (b) { b.hidden = true; });
    });
    document.addEventListener('livewire:navigated', function () {
        if (deferred) document.querySelectorAll('.install-btn').forEach(function (b) { b.hidden = false; });
        document.body.classList.toggle('is-offline', !navigator.onLine);
    });
})();
