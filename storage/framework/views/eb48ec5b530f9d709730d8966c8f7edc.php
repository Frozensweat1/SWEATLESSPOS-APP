<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?></title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="icon" href="<?php echo e($settings->FaviconUrl ?? asset('assets/img/logo.png')); ?>" type="image/x-icon">
    <meta name="theme-color" content="#ffffff">
    <script src="<?php echo e(asset('assets/js/config.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/overlayscrollbars/OverlayScrollbars.min.js')); ?>"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,500,600,700%7cPoppins:300,400,500,600,700,800,900&amp;display=swap"
        rel="stylesheet">
    <link href="<?php echo e(asset('vendors/overlayscrollbars/OverlayScrollbars.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/css/theme-rtl.min.css')); ?>" rel="stylesheet" id="style-rtl">
    <link href="<?php echo e(asset('assets/css/theme.min.css')); ?>" rel="stylesheet" id="style-default">
    <link href="<?php echo e(asset('assets/css/user-rtl.min.css')); ?>" rel="stylesheet" id="user-style-rtl">
    <link href="<?php echo e(asset('assets/css/user.min.css')); ?>" rel="stylesheet" id="user-style-default">

    <?php echo $__env->yieldPushContent('styles'); ?>

    <script>
        var isRTL = JSON.parse(localStorage.getItem('isRTL'));
        if (isRTL) {
            var linkDefault = document.getElementById('style-default');
            var userLinkDefault = document.getElementById('user-style-default');
            linkDefault.setAttribute('disabled', true);
            userLinkDefault.setAttribute('disabled', true);
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            var linkRTL = document.getElementById('style-rtl');
            var userLinkRTL = document.getElementById('user-style-rtl');
            linkRTL.setAttribute('disabled', true);
            userLinkRTL.setAttribute('disabled', true);
        }
    </script>
</head>

<body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container-fluid" data-layout="container">
            <script>
                var isFluid = JSON.parse(localStorage.getItem('isFluid'));
                if (isFluid) {
                    var container = document.querySelector('[data-layout]');
                    container.classList.add('container-fluid');
                }
            </script>
            <?php echo $__env->make('layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="content">
                <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="px-2 px-sm-3 px-md-4 py-3">
                    <?php echo e($slot); ?>

                </div>
                <?php echo $__env->make('layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

        </div>
    </main>
    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->

    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="<?php echo e(asset('vendors/popper/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/bootstrap/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/anchorjs/anchor.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/is/is.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/fontawesome/all.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendors/lodash/lodash.min.js')); ?>"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="<?php echo e(asset('vendors/list.js/list.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/theme.js')); ?>"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php echo $__env->yieldPushContent('js'); ?>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>