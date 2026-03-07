<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand py-2 py-xl-3 px-2 px-sm-3 px-md-4">
    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false"
        aria-label="Toggle Navigation">
        <span class="navbar-toggle-icon">
            <span class="toggle-line"></span>
        </span>
    </button>
    <a class="navbar-brand me-1 me-sm-3" href="<?php echo e(route('dashboard')); ?>">
        <div class="d-flex align-items-center">
            <img class="me-2" src="<?php echo e($settings->LogoUrl ?? asset('assets/img/logo.png')); ?>"
                alt="<?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?>"
                style="height: 40px; width: auto; object-fit: contain;" />
            <div class="d-flex flex-column justify-content-center">
                <span class="font-sans-serif fw-bold text-dark" style="font-size: 15px; line-height: 1.2;">
                    <?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?>

                </span>
                <small class="text-muted" style="font-size: 11px;">Management System</small>
            </div>
        </div>
    </a>
    <ul class="flex-row navbar-nav navbar-nav-icons ms-auto align-items-center">
        <li class="nav-item">
            <div class="px-2 theme-control-toggle fa-icon-wait">
                <input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle" type="checkbox"
                    data-theme-control="theme" value="dark" />
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span
                        class="fas fa-sun fs-0"></span></label>
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span
                        class="fas fa-moon fs-0"></span></label>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="px-0 nav-link notification-indicator notification-indicator-primary fa-icon-wait"
                id="navbarDropdownNotification" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="fas fa-bell" data-fa-transform="shrink-6" style="font-size: 33px;"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-card dropdown-menu-notification"
                aria-labelledby="navbarDropdownNotification">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('header-notification');

$__html = app('livewire')->mount($__name, $__params, 'lw-2430749518-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>

        </li>
        <li class="nav-item dropdown">
            <a class="nav-link pe-0" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-xl">
                    <?php if(auth()->user()->profile_photo_path): ?>
                        <!-- Assuming 'profile_image' is the image attribute -->
                        <img class="rounded-circle" src="<?php echo e(asset('storage/' . auth()->user()->profile_photo_path)); ?>"
                            alt="<?php echo e(auth()->user()->name); ?>" />
                    <?php else: ?>
                        <!-- Generate initials if no profile image -->
                        <div class="text-white rounded-circle d-flex align-items-center justify-content-center bg-dark fs-2"
                            style="width: 2rem; height: 2rem;">
                            <?php echo e(strtoupper(collect(explode(' ', auth()->user()->name))->take(2)->map(fn($name) => $name[0])->join(''))); ?>

                        </div>
                    <?php endif; ?>
                </div>

            </a>
            <div class="py-0 dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                <div class="py-2 bg-white dark__bg-1000 rounded-2">

                    <a class="dropdown-item" href="<?php echo e(route('user.profile')); ?>">
                        <?php echo e(explode(' ', Auth::user()->name)[0]); ?>

                    </a>
                    <a class="dropdown-item" href="<?php echo e(route('system.settings')); ?>">Settings</a>
                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                        <?php echo csrf_field(); ?>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/layouts/partials/header.blade.php ENDPATH**/ ?>