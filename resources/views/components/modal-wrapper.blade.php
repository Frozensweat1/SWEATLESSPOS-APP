@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'modal-lg',
    'bgColor' => 'bg-primary',
    'icon' => '',
    'show' => false,
    'closeEvent' => 'closeModal',
])

@if($show)
    <div class="modal fade show" id="{{ $id }}" data-bs-keyboard="false" data-bs-backdrop="static" style="display: block;" wire:ignore.self>
        <div class="modal-dialog {{ $size }} modal-dialog-scrollable">
            <div class="modal-content">
                <div class="text-white modal-header {{ $bgColor }}">
                    <h5 class="modal-title">
                        @if($icon)<i class="{{ $icon }} me-2"></i>@endif
                        {{ $title }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="$dispatch('{{ $closeEvent }}')"></button>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                </div>
                @if(isset($footer))
                    <div class="modal-footer">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
@endif