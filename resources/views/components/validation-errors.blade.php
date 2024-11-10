@if ($errors->any())
    <div {{ $attributes }}>
        <div class="alert alert-danger" role="alert">
            <h4 class="alert-heading fw-semi-bold">{{ __('Error!') }}</h4>
            <hr />
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
          </div>
    </div>
@endif
