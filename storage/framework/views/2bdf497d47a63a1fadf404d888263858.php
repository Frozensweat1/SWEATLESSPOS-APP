<div>
    
    <div class="card card-notification shadow-none">
        <div class="card-header">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto">
                    <h6 class="card-header-title mb-0">Notifications</h6>
                </div>
                <div class="col-auto ps-0 ps-sm-3">
                    <button wire:click="markAllAsRead" class="card-link fw-normal border-0 bg-transparent">Mark all as
                        read</button>
                </div>
            </div>
        </div>

        <div class="scrollbar-overlay" style="max-height:19rem">
            <div class="list-group list-group-flush fw-normal fs--1">
                <div class="list-group-title border-bottom">NEW</div>

                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item">
                        <a class="notification notification-flush <?php echo e($notification->is_read ? '' : 'notification-unread'); ?>"
                            href="#!">
                            <div class="notification-avatar">
                                <div class="avatar avatar-2xl me-3">
                                    <!--[if BLOCK]><![endif]--><?php if($notification->user && $notification->user->profile_photo): ?>
                                        <img class="rounded-circle"
                                            src="<?php echo e(asset($notification->user->profile_photo)); ?>"
                                            alt="<?php echo e($notification->user->name); ?>" />
                                    <?php else: ?>
                                        <div class="avatar-name rounded-circle">
                                            <span><?php echo e(substr($notification->user->name ?? 'N/A', 0, 2)); ?></span>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                            <div class="notification-body">
                                <p class="mb-1"><strong><?php echo e($notification->user->name ?? 'System'); ?></strong>
                                    <?php echo e($notification->title); ?>: "<?php echo e($notification->message); ?>"</p>
                                <span class="notification-time">
                                    <span class="me-2" role="img"
                                        aria-label="Emoji">📅</span><?php echo e($notification->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="card-footer text-center border-top">
            <a class="card-link d-block" href="<?php echo e(route('manage.notifications')); ?>">View all</a>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/header-notification.blade.php ENDPATH**/ ?>