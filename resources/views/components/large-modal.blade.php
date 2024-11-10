<div class="modal fade" id="{{ $show }}" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg mt-6" role="document">
        <div class="modal-content border-0">
            <div class="position-absolute top-0 end-0 mt-3 me-3 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bg-light rounded-top-lg py-3 ps-4 pe-6">
                    <h4 class="mb-1" id="staticBackdropLabel">{{ $title }}</h4>
                </div>
                <div class="p-4">
                    <div class="row">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-falcon-danger rounded-pill me-1 mb-1 float-end"
                    wire:click="$set('{{ $show }}', false)">Close</button>
                <button type="button" class="btn btn-sm btn-falcon-primary rounded-pill me-1 mb-1 float-end"
                    wire:click="{{ $action }}"><span class="fas fa-save"></span>{{ $buttonText }}</button>
            </div>
        </div>
    </div>
</div>
