<div id="nav">
    <nav class="navbar navbar-expand-md fixed-top interface-dark-red-bg">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                {{ config('app.name', 'ServerSide') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    @auth
                        <a class="nav-link text-white" href="{{ url('/home') }}">Home</a>
                        <a class="nav-link text-white" href="{{ url('/servers') }}">Servers list</a>
                    @endauth
                </ul>
                <!-- /Left Side Of Navbar -->

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                        <li>
                            <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                        </li>
                        <li>
                            <a class="nav-link text-white" href="{{ route('register') }}">Register</a>
                        </li>
                        @else
                            <li>
                                <a class="nav-link text-white" href="/update">Update database</a>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endguest
                </ul>
                <!-- /Right Side Of Navbar -->

            </div>
        </div>
    </nav>
</div>