<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
        </div>
        <div class="sidebar-brand-text mx-3"> <img src="{{"/admin/img/logoshop.png"}}" width="120px" height="85px" alt=""> </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>



    <!-- Nav Item - Tables -->
    <li class="nav-item active">
        <a class="nav-link " href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Category</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link " href="{{ route('capacities.index') }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>Capacity</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link " href="{{ route('colors.index') }}">
            <i class="fas fa fa-certificate"></i>
            <span>Color</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('products.index') }}">
            <i class="fas fa-fw fa-window-restore"></i>
            <span>Product</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('coupons.index') }}">
            <i class="fas fa-fw fa-window-restore"></i>
            <span>Coupon</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard.account') }}">
            <i class="fas fa-users"></i>
            <span>Account</span></a>
    </li>
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard.cart') }}">
            <i class="fas fa fa-shopping-cart"></i>
            <span>Cart</span></a>
    </li>

</ul>
