<?php if($errors->any()): ?>
    <div <?php echo e($attributes); ?>>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading fw-semi-bold"><?php echo e(__('Error!')); ?></h4>
            <hr />
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/components/validation-errors.blade.php ENDPATH**/ ?>