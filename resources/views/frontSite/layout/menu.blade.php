<div class="container-fluid bg-faded fh5co_padd_mediya padding_786">
    <div class="container padding_786">
        <nav class="navbar navbar-toggleable-md navbar-light ">
            <button class="navbar-toggler navbar-toggler-right mt-3" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation"><span class="fa fa-bars"></span></button>
            <a class="navbar-brand" href="#"><img src='{{ asset("images/logo.png") }}' class="mobile_logo_width"
                                                  alt="img"/></a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('frontSite.home') }}">Home <span
                                class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('blog') ? 'active' : '' }}">
                        <a class="nav-link" href='{{ route('frontSite.blog') }}'>Blog <span
                                class="sr-only">(current)</span></a>

                    </li>
                    <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('single') ? 'active' : '' }}">
                        <a class="nav-link" href='{{ route('frontSite.single') }}'>Single <span class="sr-only">(current)</span></a>
                    </li>

                    <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('contact') ? 'active' : '' }}">
                        <a class="nav-link" href='{{ route('frontSite.contact') }}'>Contact <span class="sr-only">(current)</span></a>
                    </li>

                </ul>

                <!-- Authentication Links -->
                <ul class="navbar-nav ml-auto">
                    @guest
                        @if (\Illuminate\Support\Facades\Route::has('login'))
                            <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('login') ? 'active' : '' }}">
                                <a class="nav-link" href='{{ route('login') }}'>{{ __('Login') }}<span
                                        class="sr-only">(current)</span></a>
                            </li>
                        @endif

                        @if (\Illuminate\Support\Facades\Route::has('register'))
                            <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('register') ? 'active' : '' }}">
                                <a class="nav-link" href='{{ route('register') }}'>{{ __('Register') }}<span
                                        class="sr-only">(current)</span></a>
                            </li>
                        @endif

                    @endguest

                    @auth
                        <li class="nav-item {{ \Illuminate\Support\Facades\Request::is('dashboard/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard.') }}">Dashboard</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endauth

                </ul>
            </div>
        </nav>
    </div>
</div>
