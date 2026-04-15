  <footer class="footer">
      <div class="row g-0 justify-content-between fs--1 mt-4 mb-3">
          <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600">All Rights Reserved
                  <span class="d-none d-sm-inline-block">|
                  </span><br class="d-sm-none" /> {{ now()->format('Y') }} &copy;
                  <a href="{{ route('website.home') }}">
                      {{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}
                  </a>Powered By <b>FrozenBytes</b>
              </p>
          </div>
          <div class="col-12 col-sm-auto text-center">
              <p class="mb-0 text-600">v2.0</p>
          </div>
      </div>
  </footer>
