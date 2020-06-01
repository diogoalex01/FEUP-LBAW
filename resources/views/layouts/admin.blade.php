<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/af172a0b3b.js" crossorigin="anonymous"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"
        defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"
        defer></script>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/home.css')}}">
    <link rel="stylesheet" href="{{ asset('css/profile.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/pear_logo.png') }}">

    <script src={{ asset('js/common.js') }} defer></script>
    <script src={{ asset('js/post.js') }} defer></script>
    <script src={{ asset('js/user.js') }} defer></script>
    <script src={{ asset('js/home.js') }} defer></script>
    <script src={{ asset('js/admin.js') }} defer></script>

    <title> {{$title}} </title>
</head>

<body>

    <!-- Navigation -->
    <nav id="topBar" class="navbar navbar-expand-lg navbar-dark">

        <a class="navbar-brand" href={{route('admin.home')}}>
            <img src={{ asset('img/pear_logo.png') }} width="67" height="50" alt="logo">
        </a>

        <!--<a class="navbar-brand" href="admin.php?auth=&admin=">
            <img src="./images/pear_logo.png" width="67" height="50" alt="logo">
        </a>-->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="form-inline my-2 mr-3 my-lg-0 mx-auto" action="{{ route('search')}}" method="get">
                @csrf
                <input id="search-bar" class="form-control mr-sm-2" type="search" name="query" placeholder="Explore"
                    aria-label="Explore">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Explore</button>
            </form>

            @if (Auth::guard('admin')->check())

            <div class="dropdown dropdown-nav">
                <div role="link" class="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="profile-pic-small" id="profileNav" height="50" width="50"
                        src="{{ asset('img/avatar_male.png') }}" alt="Profile Image">
                </div>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href={{ route('admin.logout') }}>Log Out</a>
                </div>
            </div>

            @else

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="" id="sign-up-nav-btn" data-toggle="modal"
                        data-target="#modalWelcome">Sign
                        up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" id="log-in-nav-btn" data-toggle="modal" data-target="#modalWelcome">Log
                        in</a>
                </li>
            </ul>

            @endif

        </div>
    </nav>
    <!-- Navigation -->

    <main>
        {{-- <header> --}}
        {{-- <h1><a href="{{ url('/cards') }}">Thingy!</a></h1> --}}
        {{-- @if (Auth::check())
        <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
        @endif --}}
        {{-- </header> --}}
        <section id="content">
            @yield('content')
        </section>
    </main>

    <!-- Footer -->
    <footer id="upper-footer" class="page-footer font-small">
        <div class="container">
            <div class="row">
                <div class="col-md-12 py-4">
                    <div class="mb-5 flex-center">
                        <!-- Logo -->
                        <!-- <div>
                      <img src="./images/pear_logo.png" width="67" height="50" alt="logo">
                  </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Elements -->

        <!-- Copyright -->
        <div class="footer-copyright py-3 medium text-white-50 text-center">
            <div class="mx-auto">
                LBAW © 2020 Copyright
            </div>
            <div class="mx-auto">
                <a id="about" href="{{route('about')}}"> About Us</a>
            </div>
        </div>
    </footer>

    <!-- Footer -->
</body>