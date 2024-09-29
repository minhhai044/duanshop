<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

    <div class="container">
        <a class="navbar-brand" href="index.html">Furni<span>.</span></a>

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
                <li><a class="nav-link" href="{{ route('blog') }}">Blog</a></li>
                <li><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <ul class="navbar-nav mb-2 mb-md-0 ms-5 dropdown">
                <li style="width: 34px; height:44px "><a class="nav-link " href="#"><img src="/client/images/search.svg"></a></li>
                <li class="mx-3" style="width: 34px; height:44px "><a href="" class="nav-link" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"><img src="/client/images/user.svg"></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#">My account</a></li>
                        
                        @if (Auth::user())
                        @if (Auth::user()->type === 'admin')
                        <li><a class="dropdown-item" href="{{route('dashboard')}}">Dashboard admin</a></li>
                        @endif
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

                <li style="width: 34px; height:44px "><a class="nav-link" href="{{ route('cart') }}"><img src="/client/images/cart.svg"></a></li>
            </ul>
        </div>
    </div>

</nav>
