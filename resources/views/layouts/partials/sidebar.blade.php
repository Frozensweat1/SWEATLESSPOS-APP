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
        <a class="navbar-brand d-none d-md-flex align-items-center ms-2" href="{{ route('dashboard') }}">
            <div class="d-flex align-items-center w-100 mt-3">
                <img class="navbar-logo" src="{{ $settings->LogoUrl ?? asset('assets/img/logo.png') }}"
                    alt="{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}"
                    style="height: 36px; width: auto; object-fit: contain;" />
                <div class="ms-2 d-flex flex-column justify-content-center">
                    <span class="font-sans-serif fw-bold text-dark"
                        style="font-size: 15px; line-height: 1.2;">{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="px-3 py-2 navbar-vertical-content scrollbar px-xl-0">
            @role('super-admin|admin')
                @php
                    $stores = \App\Models\Store::where('is_active', true)
                        ->orderBy('name')
                        ->get(['id', 'name']);
                    $currentStoreId = session('current_store_id') ?? auth()->user()?->store_id;
                @endphp
                <div class="mb-3 px-2">
                    <label for="sidebar-store-selector" class="form-label text-uppercase text-600 fw-bold mb-1"
                        style="font-size: 10px;">
                        Active Store
                    </label>
                    <form action="{{ route('switch.store') }}" method="POST">
                        @csrf
                        <select id="sidebar-store-selector" name="store_id" class="form-select form-select-sm"
                            onchange="this.form.submit()">
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" @selected((int) $currentStoreId === (int) $store->id)>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endrole
            @role('cashier')
                @php
                    $assignedStoreName = auth()->user()?->store?->name;
                @endphp
                @if ($assignedStoreName)
                    <div class="mb-3 px-2">
                        <div class="form-label text-uppercase text-600 fw-bold mb-1" style="font-size: 10px;">
                            Active Store
                        </div>
                        <div class="small text-muted">{{ $assignedStoreName }}</div>
                    </div>
                @endif
            @endrole

            <ul class="mb-3 navbar-nav flex-column" id="navbarVerticalNav">
                <!-- Dashboard Section -->
                @role('super-admin|admin|cashier')
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ Request::routeIs('dashboard', 'sales.dashboard') ? 'active' : '' }}"
                            href="#dashboard" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="dashboard">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                                <span class="nav-link-text ps-1">Dashboard</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ Request::routeIs('dashboard', 'sales.dashboard') ? 'show' : '' }}"
                            id="dashboard">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">General</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('sales.dashboard') ? 'active' : '' }}"
                                    href="{{ route('sales.dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Analytics</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                <!-- User Management Section (Super Admin Only) -->
                @role('super-admin')
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.roles', 'manage.users', 'add.users', 'manage.stores') ? 'active' : '' }}"
                            href="#users" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="users">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text ps-1">User Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ Request::routeIs('manage.roles', 'manage.users', 'add.users', 'manage.stores') ? 'show' : '' }}"
                            id="users">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.roles') ? 'active' : '' }}"
                                    href="{{ route('manage.roles') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Roles/Permissions Mgt</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.users') ? 'active' : '' }}"
                                    href="{{ route('manage.users') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Users</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('add.users') ? 'active' : '' }}"
                                    href="{{ route('add.users') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Add User</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Store Management -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('manage.stores') ? 'active' : '' }}"
                            href="{{ route('manage.stores') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-sitemap"></span></span>
                                <span class="nav-link-text ps-1">Manage Stores</span>
                            </div>
                        </a>
                    </li>
                @endrole

                <!-- Suppliers Management (Admin and Super Admin) -->
                @role('super-admin|admin')
                    <!-- Customers Management -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('manage.customers') ? 'active' : '' }}"
                            href="{{ route('manage.customers') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users-cog"></span></span>
                                <span class="nav-link-text ps-1">Manage Customers</span>
                            </div>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('manage.suppliers') ? 'active' : '' }}"
                            href="{{ route('manage.suppliers') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-truck"></span></span>
                                <span class="nav-link-text ps-1">Manage Suppliers</span>
                            </div>
                        </a>
                    </li>
                    <!-- Product Management -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.categories', 'manage-products', 'stock.adjustments', 'reorder.alerts', 'purchase.orders') ? 'active' : '' }}"
                            href="#products" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="products">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-boxes"></span></span>
                                <span class="nav-link-text ps-1">Product Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ Request::routeIs('manage.categories', 'manage-products', 'stock.adjustments', 'reorder.alerts', 'purchase.orders') ? 'show' : '' }}"
                            id="products">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.categories') ? 'active' : '' }}"
                                    href="{{ route('manage.categories') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Categories</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage-products') ? 'active' : '' }}"
                                    href="{{ route('manage-products') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Stock</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('stock.adjustments') ? 'active' : '' }}"
                                    href="{{ route('stock.adjustments') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Stock Adjustments</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('reorder.alerts') ? 'active' : '' }}"
                                    href="{{ route('reorder.alerts') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Reorder Alerts</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('purchase.orders') ? 'active' : '' }}"
                                    href="{{ route('purchase.orders') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Purchase Orders</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                <!-- Sales/Transactions Section (Cashier and Above) -->
                @role('super-admin|admin|cashier')
                    <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.sales', 'make.sales', 'manage.expenditure', 'manage.returns', 'cash.drawer') ? 'active' : '' }}"
                        href="#sales" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="sales">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                            <span class="nav-link-text ps-1">Sales/Transactions</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ Request::routeIs('manage.sales', 'make.sales', 'manage.expenditure', 'manage.returns', 'cash.drawer') ? 'show' : '' }}"
                        id="sales">
                        <li class="nav-item">
                            <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.sales', 'make.sales') ? 'active' : '' }}"
                                href="#sell" data-bs-toggle="collapse" aria-expanded="false" aria-controls="sell">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Sales</span>
                                </div>
                            </a>
                            <ul class="nav collapse {{ Request::routeIs('manage.sales', 'make.sales') ? 'show' : '' }}"
                                id="sell">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('manage.sales') ? 'active' : '' }}"
                                        href="{{ route('manage.sales') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">Sales List</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('make.sales') ? 'active' : '' }}"
                                        href="{{ route('make.sales') }}">
                                        <div class="d-flex align-items-center">
                                            <span class="nav-link-text ps-1">New Sale</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('manage.expenditure') ? 'active' : '' }}"
                                href="{{ route('manage.expenditure') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Expenses</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('cash.drawer') ? 'active' : '' }}"
                                href="{{ route('cash.drawer') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Cash Drawer</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('manage.returns') ? 'active' : '' }}"
                                href="{{ route('manage.returns') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Refunds/Returns</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('manage.debtors') ? 'active' : '' }}"
                                href="{{ route('manage.debtors') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Manage Debtors</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                @endrole

                <!-- Reports Section -->
                @role(['super-admin', 'admin', 'cashier'])
                    <a class="nav-link dropdown-indicator {{ Request::routeIs('sale.report', 'inventory.report', 'customer.report', 'financial.report', 'daily.sales.summary', 'product.performance', 'staff.performance', 'category.performance', 'expenditure.report', 'customerreport.detail') ? 'active' : '' }}"
                        href="#reports" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="reports">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-file-alt"></span></span>
                            <span class="nav-link-text ps-1">Reports</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ Request::routeIs('sale.report', 'inventory.report', 'customer.report', 'financial.report', 'daily.sales.summary', 'product.performance', 'staff.performance', 'category.performance', 'expenditure.report', 'customerreport.detail') ? 'show' : '' }}"
                        id="reports">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('sale.report') ? 'active' : '' }}"
                                href="{{ route('sale.report') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Sales Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('inventory.report') ? 'active' : '' }}"
                                href="{{ route('inventory.report') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Inventory Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('financial.report') ? 'active' : '' }}"
                                href="{{ route('financial.report') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Financial Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('daily.sales.summary') ? 'active' : '' }}"
                                href="{{ route('daily.sales.summary') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Daily Sales Summary</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('product.performance') ? 'active' : '' }}"
                                href="{{ route('product.performance') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Product Performance</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('staff.performance') ? 'active' : '' }}"
                                href="{{ route('staff.performance') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Staff Performance</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('category.performance') ? 'active' : '' }}"
                                href="{{ route('category.performance') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Category Performance</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('expenditure.report') ? 'active' : '' }}"
                                href="{{ route('expenditure.report') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Expenditure Report</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item border-top my-2"></li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('customer.report') ? 'active' : '' }}"
                                href="{{ route('customer.report') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Customer Report</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                @endrole

                <!-- Notifications Section -->
                @role(['super-admin', 'admin'])
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('manage.notifications') ? 'active' : '' }}"
                            href="{{ route('manage.notifications') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-bell"></span></span>
                                <span class="nav-link-text ps-1">Notifications</span>
                            </div>
                        </a>
                    </li>
                @endrole

                <!-- System Logs Section -->
                @role('super-admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('system.logs') ? 'active' : '' }}"
                            href="{{ route('system.logs') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-list"></span></span>
                                <span class="nav-link-text ps-1">System Logs</span>
                            </div>
                        </a>
                    </li>
                @endrole

                <!-- Settings Section -->
                @role('super-admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('system.settings') ? 'active' : '' }}"
                            href="{{ route('system.settings') }}">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-cogs"></span></span>
                                <span class="nav-link-text ps-1">Settings</span>
                            </div>
                        </a>
                    </li>

                    <!-- Website Management Section -->
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.website.pages', 'manage.gallery', 'manage.website.settings', 'manage.contact.submissions', 'manage.testimonials', 'manage.newsletter', 'analytics.dashboard', 'activity.logs') ? 'active' : '' }}"
                            href="#website" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="website">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-globe"></span></span>
                                <span class="nav-link-text ps-1">Website & Analytics</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ Request::routeIs('manage.website.pages', 'manage.gallery', 'manage.website.settings', 'manage.contact.submissions', 'manage.testimonials', 'manage.newsletter', 'analytics.dashboard', 'activity.logs') ? 'show' : '' }}"
                            id="website">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.website.pages') ? 'active' : '' }}"
                                    href="{{ route('manage.website.pages') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Pages</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.gallery') ? 'active' : '' }}"
                                    href="{{ route('manage.gallery') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Manage Gallery</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.website.settings') ? 'active' : '' }}"
                                    href="{{ route('manage.website.settings') }}">
                                    <div class="d-flex align-items-center">
                                        <span class="nav-link-text ps-1">Website Settings</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item border-top my-2"></li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.contact.submissions') ? 'active' : '' }}"
                                    href="{{ route('manage.contact.submissions') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope fa-sm me-2"></i>
                                        <span class="nav-link-text ps-1">Contact Submissions</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.testimonials') ? 'active' : '' }}"
                                    href="{{ route('manage.testimonials') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star fa-sm me-2"></i>
                                        <span class="nav-link-text ps-1">Testimonials</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('manage.newsletter') ? 'active' : '' }}"
                                    href="{{ route('manage.newsletter') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-newspaper fa-sm me-2"></i>
                                        <span class="nav-link-text ps-1">Newsletter</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('analytics.dashboard') ? 'active' : '' }}"
                                    href="{{ route('analytics.dashboard') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-chart-line fa-sm me-2"></i>
                                        <span class="nav-link-text ps-1">Analytics</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('activity.logs') ? 'active' : '' }}"
                                    href="{{ route('activity.logs') }}">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-history fa-sm me-2"></i>
                                        <span class="nav-link-text ps-1">Activity Logs</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endrole

                <!-- Logout Section -->
                <a class="nav-link text-danger" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <div class="d-flex align-items-center">
                        <span class="nav-link-icon"><span class="fas fa-sign-out-alt"></span></span>
                        <span class="nav-link-text ps-1">Logout</span>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </ul>
        </div>
    </div>
</nav>
