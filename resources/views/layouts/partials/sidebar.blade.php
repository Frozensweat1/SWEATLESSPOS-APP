<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                data-bs-placement="left" title="Toggle Navigation">
                <span class="navbar-toggle-icon">
                    <span class="toggle-line"></span>
                </span>
            </button>
        </div>
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <div class="d-flex align-items-center py-3">
                <img class="me-2" src="{{ 'storage/' . $settings->logo ?? asset('assets/img/logo.png') }}"
                    alt="" width="30" />
                <span class="font-sans-serif">{{ $settings->site_name ?? config('app.name', 'Sweatless-POS') }}</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content scrollbar">
            <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
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
                        <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.roles', 'manage.users', 'add.users') ? 'active' : '' }}"
                            href="#users" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="users">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text ps-1">User Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ Request::routeIs('manage.roles', 'manage.users', 'add.users') ? 'show' : '' }}"
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
                        <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.categories', 'manage-products') ? 'active' : '' }}"
                            href="#products" role="button" data-bs-toggle="collapse" aria-expanded="false"
                            aria-controls="products">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-boxes"></span></span>
                                <span class="nav-link-text ps-1">Product Management</span>
                            </div>
                        </a>
                        <ul class="nav collapse {{ Request::routeIs('manage.categories', 'manage-products') ? 'show' : '' }}"
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
                        </ul>
                    </li>
                @endrole

                <!-- Sales/Transactions Section (Cashier and Above) -->
                @role('super-admin|admin|cashier')
                    <a class="nav-link dropdown-indicator {{ Request::routeIs('manage.sales', 'make.sales', 'manage.expenditure', 'manage.returns') ? 'active' : '' }}"
                        href="#sales" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="sales">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-shopping-cart"></span></span>
                            <span class="nav-link-text ps-1">Sales/Transactions</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ Request::routeIs('manage.sales', 'make.sales', 'manage.expenditure', 'manage.returns') ? 'show' : '' }}"
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
                            <a class="nav-link {{ Request::routeIs('manage.returns') ? 'active' : '' }}"
                                href="{{ route('manage.returns') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Refunds/Returns</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                @endrole

                <!-- Reports Section -->
                @role(['super-admin', 'admin', 'cashier'])
                    <a class="nav-link dropdown-indicator {{ Request::routeIs('sale.report', 'inventory.report', 'customer.report', 'financial.report') ? 'active' : '' }}"
                        href="#reports" role="button" data-bs-toggle="collapse" aria-expanded="false"
                        aria-controls="reports">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-file-alt"></span></span>
                            <span class="nav-link-text ps-1">Reports</span>
                        </div>
                    </a>
                    <ul class="nav collapse {{ Request::routeIs('sale.report', 'inventory.report', 'customer.report', 'financial.report', 'customerreport.detail') ? 'show' : '' }}"
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
                            <a class="nav-link {{ Request::routeIs('customer.report') ? 'active' : '' }}"
                                href="{{ route('customer.report') }}">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-text ps-1">Customer Report</span>
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
