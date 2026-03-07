<a class="d-flex flex-center mb-4" href="<?php echo e(route('home')); ?>">
    <img class="me-2" src="<?php echo e($settings->LogoUrl ?? asset('assets/img/logo.png')); ?>" alt=""
        width="58" />
    <span
        class="font-sans-serif fw-bolder fs-5 d-inline-block"><?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?></span>
</a>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/components/authentication-card-logo.blade.php ENDPATH**/ ?>