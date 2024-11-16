<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

    <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}"><img width="100px" height="65px"
                src="{{ '/client/images/logoshop.png' }}" alt=""></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni"
            aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsFurni">
            <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0" id="navbar">
                <li><a class="nav-link" href="{{ route('index') }}">Home</a></li>
                <li><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                <li><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li><a class="nav-link" href="{{ route('services') }}">Services</a></li>
                <li><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <ul class="navbar-nav mb-2 mb-md-0 ms-5 dropdown">
                <li style="width: 34px; height:44px " class="dropstart">
                    <a class="nav-link " href="#" data-bs-toggle="dropdown" aria-expanded="false"><img
                            src="/client/images/search.svg"></a>
                    <ul class="dropdown-menu">

                        <form action="{{ route('search') }}" class="dropdown-item" method="post">
                            @csrf
                            <input type="text" name="keysearch" id="keysearch">
                            <button type="submit">Search</button>

                        </form>
                        <ul class="dropdown-menu show w-100 mt-3" id="listmenu">

                        </ul>
                    </ul>
                </li>
                <li class="mx-3" style="width: 34px; height:44px "><a href="" class="nav-link"
                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><img
                            src="/client/images/user.svg"></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">


                        @if (Auth::user())
                            @if (Auth::user()->type === 'admin')
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard admin</a></li>
                            @endif
                            <li><a class="dropdown-item" href="#">My account</a></li>
                            <li><a class="dropdown-item" href="{{ route('listorders') }}">My Orders</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        @endif



                    </ul>
                </li>

                <li style="width: 34px; height:44px "><a class="nav-link" href="{{ route('listcart') }}"><img
                            src="/client/images/cart.svg"></a></li>
            </ul>
        </div>
    </div>
</nav>
