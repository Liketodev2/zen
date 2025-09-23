<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand p-0" href="{{ route('home') }}">
            <img src="{{ asset('img/logo.png?v=' . time()) }}" alt="Logo" style="max-width: 100px" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav m-auto gap-5 row-gap-1">
                <li>
                    <a href="{{ route('home') }}" class="navbar_link">Home</a>
                </li>
                <li>
                    <a href="{{ route('services') }}" class="navbar_link">Services</a>
                </li>
                <li>
                    <a href="{{ route('contact') }}" class="navbar_link">Contact Us</a>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav">
                <!-- Authentication Links -->
                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="navbar_btn" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle navbar_link" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a class="dropdown-item navbar_link" href="{{ route('admin.dashboard') }}">
                                    Admin panel
                                </a>
                            @endif
                        @endauth
                        <a class="dropdown-item navbar_link" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>