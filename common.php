<?php

function draw_header()
{
?>

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/af172a0b3b.js" crossorigin="anonymous"></script>

        <!-- JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous" defer></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" defer></script>

        <link rel="stylesheet" href="css/home.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

        <title>Home</title>
    </head>
<?php
}

function draw_navigation()
{
?>
    <!-- Navigation -->
    <nav id="topBar" class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="home.html">
            <img src="./images/pear_logo.png" width="66.66" height="50" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="form-inline my-2 mr-3 my-lg-0 mx-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Explore" aria-label="Explore">
                <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Explore</button>
            </form>

            <div class="dropdown show">
                <a class="dropdown" href="" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"> <img id="login" height="50" width="50" src="./images/avatar_male.png"
                        alt="Profile Image"></a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="myProfile.html">My Account</a>
                    <a class="dropdown-item" href="settings.html">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="homeUnauth.html">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Navigation -->
<?php
}

function draw_footer()
{
?>
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
        <div class="footer-copyright text-center py-3 medium text-white-50">LBAW © 2020 Copyright:
            <a id="about" href="about.html"> about</a>
        </div>
    </footer>
    <!-- Footer -->
<?php
}
?>