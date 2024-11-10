<nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">
    <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false"
        aria-label="Toggle Navigation">
        <span class="navbar-toggle-icon">
            <span class="toggle-line"></span>
        </span>
    </button>
    <a class="navbar-brand me-1 me-sm-3" href="{{ route('dashboard') }}">
        <div class="d-flex align-items-center">
            <img class="me-2" src="../assets/img/icons/spot-illustrations/falcon.png" alt="" width="40" />
            <span class="font-sans-serif">sweatless</span>
        </div>
    </a>
    <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
        <li class="nav-item">
            <div class="theme-control-toggle fa-icon-wait px-2">
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
            <a class="nav-link notification-indicator notification-indicator-primary px-0 fa-icon-wait"
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
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-dark text-white fs-2"
                            style="width: 2rem; height: 2rem;">
                            {{ strtoupper(collect(explode(' ', auth()->user()->name))->take(2)->map(fn($name) => $name[0])->join('')) }}
                        </div>
                    @endif
                </div>

            </a>
            <div class="dropdown-menu dropdown-menu-end py-0" aria-labelledby="navbarDropdownUser">
                <div class="bg-white dark__bg-1000 rounded-2 py-2">

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
