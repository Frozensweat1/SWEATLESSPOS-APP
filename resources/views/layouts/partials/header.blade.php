<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand py-2 py-xl-3 px-2 px-sm-3 px-md-4">
    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false"
        aria-label="Toggle Navigation">
        <span class="navbar-toggle-icon">
            <span class="toggle-line"></span>
        </span>
    </button>
    <a class="navbar-brand me-1 me-sm-3" href="{{ route('dashboard') }}">
        <div class="d-flex align-items-center">
            <img class="me-2" src="{{ $settings->LogoUrl ?? asset('assets/img/logo.png') }}"
                alt="{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}"
                style="height: 40px; width: auto; object-fit: contain;" />
            <div class="d-flex flex-column justify-content-center">
                <span class="font-sans-serif fw-bold text-dark" style="font-size: 15px; line-height: 1.2;">
                    {{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}
                </span>
                <small class="text-muted" style="font-size: 11px;">Management System</small>
            </div>
        </div>
    </a>
    @php
        $headerComponentName = $componentName ?? null;
        $headerPageTitle = $pageTitle ?? null;
        $routeName = request()->route()?->getName();
        $routeLabel = $routeName ? \Illuminate\Support\Str::title(str_replace('.', ' ', $routeName)) : null;
    @endphp
    @if ($headerComponentName || $headerPageTitle || $routeLabel)
        <div class="d-none d-md-flex align-items-center ms-2">
            <span class="fw-bold text-dark">
                {{ $headerComponentName ?? $routeLabel }}
                @if ($headerPageTitle)
                    <span class="text-muted"> | {{ $headerPageTitle }}</span>
                @endif
            </span>
        </div>
    @endif
    <ul class="flex-row navbar-nav navbar-nav-icons ms-auto align-items-center">
        <li class="nav-item">
            <div class="px-2 theme-control-toggle fa-icon-wait">
                <input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle" type="checkbox"
                    data-theme-control="theme" value="dark" />
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span
                        class="fas fa-sun fs-0"></span></label>
                <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span
                        class="fas fa-moon fs-0"></span></label>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="px-0 nav-link notification-indicator notification-indicator-primary fa-icon-wait"
                id="navbarDropdownNotification" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="fas fa-bell" data-fa-transform="shrink-6" style="font-size: 33px;"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-card dropdown-menu-notification"
                aria-labelledby="navbarDropdownNotification">
                @livewire('header-notification')
            </div>

        </li>
        <li class="nav-item dropdown">
            <a class="nav-link pe-0" id="navbarDropdownUser" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <div class="avatar avatar-xl">
                    @if (auth()->user()->profile_photo_path)
                        <!-- Assuming 'profile_image' is the image attribute -->
                        <img class="rounded-circle" src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}"
                            alt="{{ auth()->user()->name }}" />
                    @else
                        <!-- Generate initials if no profile image -->
                        <div class="text-white rounded-circle d-flex align-items-center justify-content-center bg-dark fs-2"
                            style="width: 2rem; height: 2rem;">
                            {{ strtoupper(collect(explode(' ', auth()->user()->name))->take(2)->map(fn($name) => $name[0])->join('')) }}
                        </div>
                    @endif
                </div>

            </a>
            <div class="py-0 dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                <div class="py-2 bg-white dark__bg-1000 rounded-2">

                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                        {{ explode(' ', Auth::user()->name)[0] }}
                    </a>
                    <a class="dropdown-item" href="{{ route('system.settings') }}">Settings</a>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggle = document.getElementById('themeControlToggle');
            if (!toggle) return;

            var currentTheme = localStorage.getItem('theme') || 'light';
            toggle.checked = currentTheme === 'dark';
            document.documentElement.classList.toggle('dark', currentTheme === 'dark');

            toggle.addEventListener('change', function(e) {
                var theme = e.target.checked ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
                document.documentElement.classList.toggle('dark', theme === 'dark');
            });
        });
    </script>
@endpush
