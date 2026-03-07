<nav class="px-0 navbar navbar-light navbar-vertical navbar-expand-xl px-xl-3">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="px-3 py-2 d-flex align-items-center px-xl-0">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation">
                <span class="navbar-toggle-icon">
                    <span class="toggle-line"></span>
                </span>
            </button>
        </div>
        <a class="navbar-brand d-none d-md-flex align-items-center ms-2" href="<?php echo e(route('dashboard')); ?>">
            <div class="d-flex align-items-center w-100">
                <img class="navbar-logo" src="<?php echo e($settings->LogoUrl ?? asset('assets/img/logo.png')); ?>"
                    alt="<?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?>"
                    style="height: 36px; width: auto; object-fit: contain;" />
                <div class="ms-2 d-flex flex-column justify-content-center">
                    <span class="font-sans-serif fw-bold text-dark"
                        style="font-size: 15px; line-height: 1.2;"><?php echo e($settings->site_name ?? config('app.name', 'Sweatless-POS')); ?></span>
                    <small class="text-muted" style="font-size: 11px;">Management System</small>
                </div>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="px-3 py-2 navbar-vertical-content scrollbar px-xl-0">
            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin')): ?>
                <?php
                    $stores = \App\Models\Store::where('is_active', true)->orderBy('name')->get(['id', 'name']);
                    $currentStoreId = session('current_store_id') ?? auth()->user()?->store_id;
                ?>
                <div class="mb-3 px-2">
                    <label for="sidebar-store-selector" class="form-label text-uppercase text-600 fw-bold mb-1"
                        style="font-size: 10px;">
                        Active Store
                    </label>
                    <form action="<?php echo e(route('switch.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <select id="sidebar-store-selector" name="store_id" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($store->id); ?>" <?php if((int) $currentStoreId === (int) $store->id): echo 'selected'; endif; ?>>
                                    <?php echo e($store->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </form>
                </div>
            <?php endif; ?>

            <ul class="mb-3 navbar-nav flex-column" id="navbarVerticalNav">
                <!-- Dashboard Section -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin|admin|cashier')): ?>
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator <?php echo e(Request::routeIs('dashboard', 'sales.dashboard') ? 'active' : ''); ?>"
                            href="#dashboard" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="dashboard">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                                <span class="nav-link-text ps-1">Dashboard</span>
                            </div>
                        </a>
                        <ul class="nav collapse <?php echo e(Request::routeIs('dashboard', 'sales.dashboard') ? 'show' : ''); ?>"
                            id="dashboard">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('dashboard') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('dashboard')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">General</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('sales.dashboard') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('sales.dashboard')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Analytics</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- User Management Section (Super Admin Only) -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator <?php echo e(Request::routeIs('manage.roles', 'manage.users', 'add.users', 'manage.stores') ? 'active' : ''); ?>"
                            href="#users" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="users">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text ps-1">User Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse <?php echo e(Request::routeIs('manage.roles', 'manage.users', 'add.users', 'manage.stores') ? 'show' : ''); ?>"
                            id="users">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('manage.roles') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('manage.roles')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Roles/Permissions Mgt</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('manage.users') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('manage.users')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Users</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('add.users') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('add.users')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Add User</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Store Management -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('manage.stores') ? 'active' : ''); ?>"
                            href="<?php echo e(route('manage.stores')); ?>">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-sitemap"></span></span>
                                <span class="nav-link-text ps-1">Manage Stores</span>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Suppliers Management (Admin and Super Admin) -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin|admin')): ?>
                    <!-- Customers Management -->
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('manage.customers') ? 'active' : ''); ?>"
                            href="<?php echo e(route('manage.customers')); ?>">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users-cog"></span></span>
                                <span class="nav-link-text ps-1">Manage Customers</span>
                            </div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('manage.suppliers') ? 'active' : ''); ?>"
                            href="<?php echo e(route('manage.suppliers')); ?>">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-truck"></span></span>
                                <span class="nav-link-text ps-1">Manage Suppliers</span>
                            </div>
                        </a>
                    </li>
                    <!-- Product Management -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator <?php echo e(Request::routeIs('manage.categories', 'manage-products') ? 'active' : ''); ?>"
                            href="#products" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="products">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-boxes"></span></span>
                                <span class="nav-link-text ps-1">Product Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse <?php echo e(Request::routeIs('manage.categories', 'manage-products') ? 'show' : ''); ?>"
                            id="products">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('manage.categories') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('manage.categories')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Categories</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::routeIs('manage-products') ? 'active' : ''); ?>"
                                    href="<?php echo e(route('manage-products')); ?>">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Stock</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Sales/Transactions Section (Cashier and Above) -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin|admin|cashier')): ?>
                    <a class="nav-link dropdown-indicator <?php echo e(Request::routeIs('manage.sales', 'make.sales', 'manage.expenditure', 'manage.returns') ? 'active' : ''); ?>"
                        href="#sales" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="sales">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                            <span class="nav-link-text ps-1">Sales/Transactions</span>
                        </div>
                    </a>
                    <ul class="nav collapse <?php echo e(Request::routeIs('manage.sales', 'make.sales', 'manage.expenditure', 'manage.returns') ? 'show' : ''); ?>"
                        id="sales">
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator <?php echo e(Request::routeIs('manage.sales', 'make.sales') ? 'active' : ''); ?>"
                                href="#sell" data-bs-toggle="collapse" aria-expanded="false" aria-controls="sell">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Sales</span>
                                </div>
                            </a>
                            <ul class="nav collapse <?php echo e(Request::routeIs('manage.sales', 'make.sales') ? 'show' : ''); ?>"
                                id="sell">
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Request::routeIs('manage.sales') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('manage.sales')); ?>">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Sales List</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo e(Request::routeIs('make.sales') ? 'active' : ''); ?>"
                                        href="<?php echo e(route('make.sales')); ?>">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">New Sale</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('manage.expenditure') ? 'active' : ''); ?>"
                                href="<?php echo e(route('manage.expenditure')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Expenses</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('manage.returns') ? 'active' : ''); ?>"
                                href="<?php echo e(route('manage.returns')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Refunds/Returns</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('manage.debtors') ? 'active' : ''); ?>"
                                href="<?php echo e(route('manage.debtors')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Manage Debtors</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <!-- Reports Section -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', ['super-admin', 'admin', 'cashier'])): ?>
                    <a class="nav-link dropdown-indicator <?php echo e(Request::routeIs('sale.report', 'inventory.report', 'customer.report', 'financial.report', 'manage.debtors') ? 'active' : ''); ?>"
                        href="#reports" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="reports">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-file-alt"></span></span>
                            <span class="nav-link-text ps-1">Reports</span>
                        </div>
                    </a>
                    <ul class="nav collapse <?php echo e(Request::routeIs('sale.report', 'inventory.report', 'customer.report', 'financial.report', 'customerreport.detail', 'manage.debtors') ? 'show' : ''); ?>"
                        id="reports">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('sale.report') ? 'active' : ''); ?>"
                                href="<?php echo e(route('sale.report')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Sales Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('inventory.report') ? 'active' : ''); ?>"
                                href="<?php echo e(route('inventory.report')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Inventory Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('customer.report') ? 'active' : ''); ?>"
                                href="<?php echo e(route('customer.report')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Customer Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(Request::routeIs('financial.report') ? 'active' : ''); ?>"
                                href="<?php echo e(route('financial.report')); ?>">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Financial Report</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <!-- Notifications Section -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', ['super-admin', 'admin'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('manage.notifications') ? 'active' : ''); ?>"
                            href="<?php echo e(route('manage.notifications')); ?>">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-bell"></span></span>
                                <span class="nav-link-text ps-1">Notifications</span>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- System Logs Section -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('system.logs') ? 'active' : ''); ?>"
                            href="<?php echo e(route('system.logs')); ?>">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-list"></span></span>
                                <span class="nav-link-text ps-1">System Logs</span>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Settings Section -->
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'super-admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(Request::routeIs('system.settings') ? 'active' : ''); ?>"
                            href="<?php echo e(route('system.settings')); ?>">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-cogs"></span></span>
                                <span class="nav-link-text ps-1">Settings</span>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Logout Section -->
                <a class="nav-link text-danger" href="<?php echo e(route('logout')); ?>"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="d-flex align-items-center">
                        <span class="nav-link-icon"><span class="fas fa-sign-out-alt"></span></span>
                        <span class="nav-link-text ps-1">Logout</span>
                    </div>
                </a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                    <?php echo csrf_field(); ?>
                </form>

            </ul>
        </div>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\sweatlesspos-app\resources\views/layouts/partials/sidebar.blade.php ENDPATH**/ ?>