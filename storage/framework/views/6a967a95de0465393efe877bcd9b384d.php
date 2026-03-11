<div>
    

    <!-- <section> begin ============================-->
    <section class="py-0 overflow-hidden light" id="banner">

        <div class="bg-holder overlay"
            style="background-image:url(<?php echo e($settings->BackgroundImg2Url ?? asset('assets/img/bg-1.jpg')); ?>);background-position: center bottom;">
        </div>
        <!--/.bg-holder-->

        <div class="container">
            <div class="row flex-center pt-8 pt-lg-10 pb-lg-9 pb-xl-0">
                <div class="col-md-11 col-lg-8 col-xl-4 pb-7 pb-xl-9 text-center text-xl-start">
                    <h1 class="text-white fw-light"><?php echo e($settings->site_name ?? 'Sweatless POS'); ?></h1>
                    <h1 class="text-white fw-light">
                        Transacting With
                        <span class="typed-text fw-bold"
                            data-typed-text='["ease","transparency","accountability","trust","efficiency"]'>
                        </span>

                    </h1>
                    <a class="btn btn-outline-light border-2 rounded-pill btn-lg mt-4 fs-0 py-2"
                        href="<?php echo e(route('dashboard')); ?>">Go To
                        Shop
                        <span class="fas fa-play ms-2" data-fa-transform="shrink-6 down-1"></span>
                    </a>
                </div>
                <div class="col-lg-4 pb-7 pb-xl-9 justify-content-end">
                    <a class="rounded" href="<?php echo e(route('dashboard')); ?>">
                       
                            <img src="<?php echo e($settings->BackgroundImg1Url ?? ''); ?>" alt="Company Logo" class="img-fluid">
                      
                    </a>
                </div>
            </div>
        </div>
        <!-- end of .container-->

    </section>
    <!-- <section> close ============================-->

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/welcome.blade.php ENDPATH**/ ?>