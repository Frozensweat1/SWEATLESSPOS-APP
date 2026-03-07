<div class="modal fade show" id="scrollinglongcontent" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-modal="true" role="dialog" style="display: block; padding-left: 0px;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="scrollinglongcontentLabel">
                    <i class="fas fa-receipt me-2"></i>Sale Receipt #<?php echo e($currentSale->id ?? 'N/A'); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                    wire:click="closeModal"></button>
            </div>
            <div class="modal-body modal-dialog modal-dialog-scrollable mt-0" id="receipt"
                style="font-family: 'Courier New', monospace; font-size: 12px; padding: 20px;">
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/livewire/modals/receipt-modal-header.blade.php ENDPATH**/ ?>