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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"
        defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"
        defer></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"
        defer></script>

    <link rel="stylesheet" href="{{ asset('css/home.css')}}">
    <link rel="stylesheet" href="{{ asset('css/profile.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('img/pear_logo.png') }}">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/common.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/post.js') }} defer></script>
    <script type="text/javascript" src={{ asset('js/user.js') }} defer></script>

    <title> {{$title}} </title>
</head>

<body>

    <!-- Navigation -->
    <nav id="topBar" class="navbar navbar-expand-lg navbar-dark">

        <a class="navbar-brand" href="{{route('home')}}">
            <img src={{ asset('img/pear_logo.png') }} width="66.66" height="50" alt="logo">
        </a>

        <!--<a class="navbar-brand" href="admin.php?auth=&admin=">
            <img src="./images/pear_logo.png" width="66.66" height="50" alt="logo">
        </a>-->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="form-inline my-2 mr-3 my-lg-0 mx-auto" action="search.php" method="get">
                {{-- <input name="auth" hidden value=" $auth "
            <input name="admin" hidden value=" $admin " --}}
                <input id="search-bar" class="form-control mr-sm-2" type="search" placeholder="Explore"
                    aria-label="Explore">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Explore</button>
            </form>

            @if (Auth::check())

            {{-- if ($admin === "false") --}}

            <div class="dropdown dropdown-nav">

                <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="">
                    <i class="fas fa-bell bell fa-lg"></i>
                </a>

                <ul class="dropdown-menu notifications dropdown-menu-right pl-3 pt-3 pb-0 mt-3" role="menu"
                    aria-labelledby="dLabel" style="background-color: #f8f9fa;">

                    <div class="notification-heading">
                        <div class="row">
                            <div class="col">
                                <h6 class="menu-title">Notifications</h6>
                            </div>
                        </div>
                    </div>

                    <li class="divider"></li>

                    <div class="notifications-wrapper">

                        <div class="notification-content" href="#">

                            <div class="notification-item">

                                <div class="row">
                                    <div class="col-3">
                                        <a href="userProfile.php">
                                            <img class="notification-pic" id="login" height="50" width="50"
                                                src="{{ asset('img/avatar_female.png') }}" alt="Profile Image"></a>
                                    </div>
                                    <div class="col-7 p-0">
                                        <h4 class="item-title"><a>@someotheruser</a> sent you a friend request</h4>
                                        <h6 class="item-info"> <i class="fas fa-calendar-alt"></i> 1 day ago</h6>
                                    </div>
                                    <div class="d-flex align-items-start pt-1">
                                        <div class="col-2">
                                            <div class="row mb-3">
                                                <a href="">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </div>
                                            <div class="row">
                                                <a href="">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <hr class="my-0" style="width: 80%;">

                        <div class="notification-content" href="#">

                            <div class="notification-item">

                                <div class="row">
                                    <div class="col-3">
                                        <a href="community.php">
                                            <img class="notification-pic" id="login" height="50" width="50"
                                                src={{ asset('img/Porto.jpg')}} alt="Profile Image"></a>
                                    </div>
                                    <div class="col-7 p-0">
                                        <h4 class="item-title"><a href="userProfile.php">@someotheruser</a> asked to
                                            join your communnity <a href="community.php">/Porto</a></h4>
                                        <h6 class="item-info"> <i class="fas fa-calendar-alt"></i> 14h ago</h6>
                                    </div>
                                    <div class="d-flex align-items-start pt-1">
                                        <div class="col-2">
                                            <div class="row mb-3">
                                                <a href="">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </div>
                                            <div class="row">
                                                <a href="">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <hr class="my-0" style="width: 80%;">

                        <div class="notification-content" href="#">
                            <div class="notification-item">
                                <div class="row">
                                    <div class="col-3">
                                        <a href="userProfile.php">
                                            <img class="notification-pic" id="login" height="50" width="50"
                                                src="{{ asset('img/avatar_male.png') }}" alt="Profile Image"></a>
                                    </div>
                                    <div class="col-7 p-0">
                                        <h4 class="item-title"><a>@someotheruser</a> sent you a friend request</h4>
                                        <h6 class="item-info"> <i class="fas fa-calendar-alt"></i> 1 day ago</h6>
                                    </div>
                                    <div class="d-flex align-items-start pt-1">
                                        <div class="col-2">
                                            <div class="row mb-3">
                                                <a href="">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </div>
                                            <div class="row">
                                                <a href="">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <li class="divider"></li>
                </ul>

            </div>

            <div class="dropdown dropdown-nav">
                @if (strlen($user->password) < 32) <a class="dropdown" href="" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false"> <img class="rounded-circle" id="login" height="50"
                        width="50" src="{{ asset($user->photo) }}" alt="Profile Image">
                    </a>
                    @else
                    <a class="dropdown" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <img
                            class="rounded-circle" id="login" height="50" width="50" src="{{ url($user->photo) }}"
                            alt="Profile Image">
                    </a>
                    @endif

                    <div class="dropdown-menu dropdown-menu-right">

                        {{-- if ($admin === "false") {--}}

                        <a class="dropdown-item" href="myProfile.php">My Account</a>
                        <a class="dropdown-item" href={{ route('settings') }}>Settings</a>
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href={{ url('/logout') }}>Log Out</a>
                    </div>
            </div>

            @else

            {{-- draw_login_modals("true", $admin); --}}

            <!-- Modal -->
            <div class="modal p-0" id="modalWelcome" tabindex="-1" role="dialog" aria-labelledby="modalWelcomeTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal md-w-75" role="document">
                    <div class="modal-content">
                        <div class="modal-body login-modal">
                            <div class="col">
                                <div class="d-flex justify-content-center">
                                    <img src="{{ asset('img/pear_logo.png') }}" width="auto" height="100" alt="logo">
                                </div>

                                <div>
                                    <section>
                                        <div class="container mb-3">
                                            <h2 class="text-center text-dark title-padding title-mobile">Welcome
                                            </h2>
                                            <div>
                                                <div class="d-flex justify-content-center">
                                                    <button id="continue-email-button" class="btn btn-secondary my-2"
                                                        data-toggle="modal" data-dismiss="modal"
                                                        data-target="#modalLogin">Continue
                                                        with Email</button>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <a href={{ url('/redirect') }}>
                                                        <div class="google-btn" style="max-width: 192px;">
                                                            <div class="google-icon-wrapper">
                                                                <img class="google-icon-svg"
                                                                    src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" />
                                                            </div>
                                                            <p class="btn-text"><b>Continue with Google</b></p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLoginTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-0">

                            <div class="row login-modal">

                                <div class="col-md-2 modal-image container pl-3 pt-8 m-0">
                                    <!-- <img src="" style="width: 350px; height: 500px; object-fit: cover" alt="logo">-->
                                </div>
                                <div class="col-md-8 my-auto mx-auto">
                                    <div class="my-auto">

                                        <div class="container">
                                            <h2 class="text-center text-dark title-padding title-mobile mb-4">Log in
                                            </h2>
                                        </div>

                                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                                            {{ csrf_field() }}

                                            <div class="row d-flex justify-content-lg-center">
                                                <div class="col-lg-10 mb-4">

                                                    <div id="feedback-message-login">
                                                    </div>

                                                    <label for="email">Email</label>
                                                    <input id="email" name="email" type="email" class="form-control"
                                                        placeholder="Email" required>

                                                </div>
                                                <div class="col-lg-10 mty-3">
                                                    <label for="password">Password</label>
                                                    <input type="password" id="password" name="password"
                                                        class="form-control" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}"
                                                        placeholder="Password" required>

                                                </div>
                                                <div class="form-group row login-signup-trans mt-4 mx-0 w-100">
                                                    <div class="row d-flex align-items-center mr-2">
                                                        <p class="m-0">Don't have an account?
                                                            <a class="mr-1 text-center" href="">
                                                                <p class="font-weight-bold m-0" data-toggle="modal"
                                                                    data-dismiss="modal" data-target="#modalSignup">
                                                                    Sign up</p>
                                                            </a>
                                                        </p>
                                                    </div>
                                                    <a href="{{ URL::to('/') }}">
                                                        <button type="submit" class="btn btn-outline-dark ">Log
                                                            in</button>
                                                    </a>
                                                </div>
                                                <div>
                                                    <button id="loginBtn" type="button" data-dismiss="modal" data-toggle="modal" data-target="#modalRecover"
                                                        class="my-auto btn">Forgot password?
                                                        <a class="mr-1 text-center" href=""></a>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal" id="modalSignup" tabindex="-1" role="dialog" aria-labelledby="modalSignupTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body p-0">
                            <section>
                                <div class="row login-modal">
                                    <div class="col-md-2 modal-image container pl-3 pt-8 m-0">
                                    </div>
                                    <div class="col-md-8 m-auto">
                                        <div class="my-auto">
                                            <div class="container">
                                                <h2 class="text-center text-dark title-padding title-mobile mb-4">Sign
                                                    up
                                                </h2>
                                            </div>

                                            <form method="POST" action="{{ route('register') }}">
                                                {{ csrf_field() }}
                                                <div class="row d-flex justify-content-center">

                                                    @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul class="my-auto">
                                                            @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endif

                                                    <div class="row d-flex justify-content-lg-center mb-2">

                                                        <div class="col sign-up-input">
                                                            <label for="firstName" class="control-label">First
                                                                name:</label>
                                                            <input class="form-control" type="text" id="firstName"
                                                                name="firstName" placeholder="First Name" required>
                                                        </div>
                                                        <div class="col sign-up-input">
                                                            <label for="lastName" class=" control-label">Last
                                                                name:</label>
                                                            <input class="form-control" type="text"
                                                                placeholder="Last Name" id="lastName" name="lastName"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="row d-flex justify-content-lg-center mb-2">
                                                        <div class="col sign-up-input">
                                                            <label for="username">Username *</label>
                                                            <div class="input-group mb-2">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">@</div>
                                                                </div>
                                                                <input type="text" class="form-control" id="username"
                                                                    name="username" placeholder="Username" required>
                                                            </div>
                                                        </div>
                                                        <div class="col sign-up-input">
                                                            <label for="email">Email *</label>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" placeholder="Email" required>
                                                        </div>
                                                    </div>
                                                    <div class="row d-flex justify-content-lg-center mb-2">
                                                        <div class="col sign-up-input">
                                                            <label for="password">Password *</label>
                                                            <input type="password" id="password" name="password"
                                                                class="form-control" aria-describedby="passwordHelp"
                                                                pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}"
                                                                placeholder="Password" required>
                                                            <div class="invalid-feedback">
                                                                Required!
                                                            </div>
                                                        </div>
                                                        <div class="col sign-up-input">
                                                            <label for="password_confirmation"> Confirm Password
                                                                *</label>
                                                            <input type="password" id="password_confirmation"
                                                                name="password_confirmation" class="form-control"
                                                                aria-describedby="passwordHelp"
                                                                pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}"
                                                                placeholder="Password" required>
                                                            <div class="invalid-feedback">
                                                                Required!
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row d-flex justify-content-lg-center mb-2">
                                                        <div class="col sign-up-input">
                                                            <label for="gender" class="control-label">Gender:</label>
                                                            <select class="form-control" id="gender" name="gender">
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col sign-up-input">
                                                            <label for="birthdate"
                                                                class="control-label">Birthdate:</label>
                                                            <input class="form-control" type="date" id="birthdate"
                                                                name="birthdate" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row login-signup-trans mt-4">
                                                        <div class="row d-flex align-items-center mr-2">
                                                            <p class="m-0">Already have an account? <a
                                                                    class="mr-1 text-center" href="">
                                                                    <p class="font-weight-bold m-0" data-toggle="modal"
                                                                        data-dismiss="modal" data-target="#modalLogin">
                                                                        Log in</p>
                                                                </a>
                                                            </p>
                                                        </div>
                                                        <a href="{{ URL::to('/') }}">
                                                            <button type="submit" onclick=""
                                                                class="btn btn-outline-dark">Sign up</button>
                                                        </a>

                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal" id="modalRecover" tabindex="-1" role="dialog" aria-labelledby="modalCommunityTitle"
                aria-hidden="false">
                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-body login-modal">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <section>
                                <form method="post" action="{{ url('/reset_password_form')}}" id="recoverPassword" class="container mb-3">
                                    @csrf
                                    <h2 class="text-dark title-padding title-mobile">Recover Password</h2>
                                    <hr>

                                    <div id="feedback-message-recover">
                                    </div>

                                    <label class="col control-label pl-0 mx-0">E-mail Address</label>
                                    <input class="form-control" type="email" name="email"
                                        id="recover-password-email" required>
                                    {{-- <input hidden type="text" value="{{$user->username}}" id="delete-user-solution"> --}}
                                    <div class="row justify-content-end my-2 mx-1">
                                        <button id="resetPassBtn" type="submit" class="btn btn-secondary my-2">Recover Password</button>
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="" id="sign-up-nav-btn" data-toggle="modal"
                        data-target="#modalWelcome">Sign up</a>
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
                      <img src="./images/pear_logo.png" width="66.66" height="50" alt="logo">
                  </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Elements -->

        <!-- Copyright -->
        <div class="footer-copyright py-3 medium text-white-50 text-center" row>
            <div class="mx-auto">
                LBAW © 2020 Copyright
            </div>
            <div class="mx-auto">
                <a id="about" href="{{route('about')}}"> About Us</a>
            </div>
    </footer>

</body>
<!-- Footer -->