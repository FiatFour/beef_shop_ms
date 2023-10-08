
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('admin-assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">BEEF SHOP</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="dashboard.html" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Category</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.sub-categories.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Sub Category</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.cow-genes.index') }}" class="nav-link">
                        <svg class="h-6 nav-icon w-6 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                          </svg>
                        <p>Cow gene</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>Products</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.shipping.create') }}" class="nav-link">
                        <!-- <i class="nav-icon fas fa-tag"></i> -->
                        <i class="fas fa-truck nav-icon"></i>
                        <p>Shipping</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <p>Orders</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link">
                        <i class="nav-icon  fa fa-percent" aria-hidden="true"></i>
                        <p>Discount</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.customers.index') }}" class="nav-link">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Customers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.employees.index') }}" class="nav-link">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Employees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pages.index') }}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Pages</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.salaries.index') }}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Salaries</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.create') }}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Suppliers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.cows.index') }}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Cows</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.order-cows.index') }}" class="nav-link">
                        <i class="nav-icon  far fa-file-alt"></i>
                        <p>Order Cows</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
 </aside>
