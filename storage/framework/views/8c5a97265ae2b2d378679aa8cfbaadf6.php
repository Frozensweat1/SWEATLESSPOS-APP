<div>
    
    <div wire:ignore.self class="modal fade show" id="themodal" data-bs-keyboard="false" data-bs-backdrop="static"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog modal-lg mt-6" role="document">
            <div class="modal-content border-0">
                <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                        <h4 class="mb-1" id="staticBackdropLabel"><b><?php echo e($componentName); ?></b> |
                            Refund
                        </h4>
                    </div>
                    <div class="p-4">
                        <div class="row">
                            <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                                <div class="alert alert-success">
                                    <?php echo e(session('message')); ?>

                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <form>
                                <div class="mb-3">
                                    <label class="form-label">Reason</label>
                                    <textarea class="form-control <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="reason" id="" cols="30"
                                        rows="5" wire:model.defer="reason"></textarea>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="closeModal"
                        class="btn btn-sm btn-falcon-danger rounded-pill me-1 mb-1 float-end"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" wire:click.prevent="processRefund()"
                        class="btn btn-sm btn-outline-primary rounded-pill me-1 d-flex align-items-center float-end"
                        wire:loading.attr="disabled" wire:loading.class="disabled"
                        wire:loading.class.remove="btn-outline-primary" wire:loading.class.add="btn-primary">
                        <!-- Spinner -->
                        <span wire:loading wire:target="processRefund" class="spinner-border spinner-border-sm me-2"
                            role="status" aria-hidden="true"></span>
                        <!-- Icon for smaller screens -->
                        <span class="d-none d-md-inline">
                            <i class="fas fa-save"></i>
                            <span wire:loading.remove wire:target="processRefund"> Process</span>
                        </span>
                        <span wire:loading wire:target="processRefund"> Processing...</span>
                        <span class="d-md-none">
                            <i class="fas fa-save"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>

</div>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/refund-formmodal.blade.php ENDPATH**/ ?>