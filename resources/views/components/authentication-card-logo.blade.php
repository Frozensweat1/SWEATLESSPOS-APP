<a class="d-flex flex-center mb-4" href="{{ route('home') }}">
    <img class="me-2" src="{{ $settings->LogoUrl ?? asset('assets/img/logo.png') }}" alt=""
        width="58" />
    <span
        class="font-sans-serif fw-bolder fs-5 d-inline-block">{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}</span>
</a>
