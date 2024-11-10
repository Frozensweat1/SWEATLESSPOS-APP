  <nav class="navbar navbar-standard navbar-expand-lg fixed-top navbar-dark"
      data-navbar-darken-on-scroll="data-navbar-darken-on-scroll">
      <div class="container">
          <a class="navbar-brand" href="{{ route('home') }}">
              <span
                  class="text-dark dark__text-white">{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}</span>
          </a>
          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStandard"
              aria-controls="navbarStandard" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse scrollbar" id="navbarStandard">

              <ul class="navbar-nav ms-auto">

                  <li class="nav-item">
                      @if (Auth::check())
                          <!-- Display user's name if authenticated -->
                          <a class="nav-link text-dark" href="{{ route('user.profile') }}" role="button">
                              {{ Auth::user()->name }}
                          </a>
                      @else
                          <!-- Display "Login" link if not authenticated -->
                          <a class="nav-link text-dark" href="{{ route('login') }}" role="button">Login</a>
                      @endif
                  </li>

              </ul>
          </div>
      </div>
  </nav>
