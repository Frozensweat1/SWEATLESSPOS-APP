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
                            <?php echo e($selected_id > 0 ? 'Edit' : 'Create'); ?>

                        </h4>
                    </div>
                    <div class="p-4">
                        <div class="row">
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/modals/large-modal-header.blade.php ENDPATH**/ ?>