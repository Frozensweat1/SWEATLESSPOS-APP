  <nav class="navbar navbar-standard navbar-expand-lg fixed-top navbar-dark"
      data-navbar-darken-on-scroll="data-navbar-darken-on-scroll">
      <div class="container">
          <a class="navbar-brand" href="<?php echo e(route('home')); ?>">
              <span
                  class="text-white dark__text-white"><?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?></span>
          </a>
          <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarStandard"
              aria-controls="navbarStandard" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse scrollbar" id="navbarStandard">

              <ul class="navbar-nav ms-auto">

                  <li class="nav-item">
                      <?php if(Auth::check()): ?>
                          <!-- Display user's name if authenticated -->
                          <a class="nav-link text-white" href="<?php echo e(route('user.profile')); ?>" role="button">
                              <?php echo e(Auth::user()->name); ?>

                          </a>
                      <?php else: ?>
                          <!-- Display "Login" link if not authenticated -->
                          <a class="nav-link text-white" href="<?php echo e(route('login')); ?>" role="button">Login</a>
                      <?php endif; ?>
                  </li>

              </ul>
          </div>
      </div>
  </nav>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/layouts/partials/guest-header.blade.php ENDPATH**/ ?>