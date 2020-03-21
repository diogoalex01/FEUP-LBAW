<?php

function draw_head($title)
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
        <link rel="stylesheet" href="css/profile.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">

        <title><?= $title ?></title>
    </head>

    <body>
        <?php draw_report_modals(); ?>
    <?php
}

function draw_navigation($auth, $studying = "", $admin = "false")
{
    ?>
        <!-- Navigation -->
        <nav id="topBar" class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="home.php?auth=<?= $auth ?>">
                <img src="./images/pear_logo.png" width="66.66" height="50" alt="logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="form-inline my-2 mr-3 my-lg-0 mx-auto" action="search.php" method="get">
                    <input name="auth" hidden value="<?= $auth ?>">
                    <input class="form-control mr-sm-2" style="width: 450px;" type="search" placeholder="Explore" value="<?= $studying ?>" aria-label="Explore">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Explore</button>
                </form>

                <?php if ($auth === "true") { ?>

                    <!--------------------------------------------------------->
                    <?php if ($admin === "false") { ?>

                        <div class="dropdown">

                            <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="">
                                <i class="fas fa-bell bell fa-lg"></i>
                            </a>

                            <ul class="dropdown-menu notifications dropdown-menu-right pl-3 pt-3 pb-0 mt-3" role="menu" aria-labelledby="dLabel" style="background-color: #f8f9fa;">

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
                                                    <a href="userProfile.php?auth=<?= $auth ?>">
                                                        <img class="notification-pic" id="login" height="50" width="50" src="./images/avatar_female.png" alt="Profile Image"></a>
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
                                                    <a href="community.php?auth=<?= $auth ?>">
                                                        <img class="notification-pic" id="login" height="50" width="50" src="./images/Porto.jpg" alt="Profile Image"></a>
                                                </div>
                                                <div class="col-7 p-0">
                                                    <h4 class="item-title"><a href="userProfile.php?auth=<?= $auth ?>">@someotheruser</a> asked to
                                                        join your communnity <a href="community.php?auth=<?= $auth ?>">/Porto</a></h4>
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
                                                    <a href="userProfile.php?auth=<?= $auth ?>">
                                                        <img class="notification-pic" id="login" height="50" width="50" src="./images/avatar_female.png" alt="Profile Image"></a>
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
                    <? } ?>

                    <!--------------------------------------------------------->
            </div>

            <div class="dropdown show">
                <a class="dropdown" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <img id="login" height="50" width="50" src="./images/avatar_male.png" alt="Profile Image"></a>

                <div class="dropdown-menu dropdown-menu-right">
                    <?php
                    if ($admin === "false") {
                    ?>
                        <a class="dropdown-item" href="myProfile.php?auth=<?= $auth ?>">My Account</a>
                        <a class="dropdown-item" href="settings.php?auth=<?= $auth ?>">Settings</a>
                        <div class="dropdown-divider"></div>
                    <?
                    }
                    ?>
                    <a class="dropdown-item" href="home.php?auth=false">Log Out</a>
                </div>
            </div>
        <?php } else { ?>
            <?
                    draw_login_modals("true");
            ?>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="" data-toggle="modal" data-target="#modalWelcome">Sign up</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="" data-toggle="modal" data-target="#modalWelcome">Log in</a>
                </li>
            </ul>
        <?php } ?>
        </div>
        </nav>
        <!-- Navigation -->

    <?php
}

function draw_footer($auth)
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
            <div class="footer-copyright text-center py-3 medium text-white-50">LBAW © 2020 Copyright
                <a id="about" href="about.php?auth=<?= $auth ?>"> About Us</a>
            </div>
        </footer>
        <!-- Footer -->

    </body>

<?php
}

function draw_report_modals()
{
?>
    <!-- Report Community Modal -->
    <div class="modal" id="modalCommunittyReport" tabindex="-1" role="dialog" aria-labelledby="modalWelcomeTitle" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <section>
                        <div class="container mb-3">
                            <h2 class="text-secondary title-padding title-mobile">Report Post
                            </h2>
                            <hr>
                            <label class="col-md-8 control-label mx-2">Could you tell us the why:</label>
                            <select class="form-control mx-4" id="gender" name="gender" style="width:96.1%">
                                <option value="spam">It's spam</option>
                                <option value="copy">It infringes my copyright</option>
                                <option value="innapropriate">Inappropriate Content</option>
                                <option value="irrelevant">Irrelevant Content</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="row justify-content-end my-2 mx-1">

                                <button class="btn btn-secondary my-2 mr-1" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Close</button>
                                <button class="btn btn-danger my-2 ml-1" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Send
                                    Report</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Post Modal -->
    <div class="modal" id="modalPostReport" tabindex="-1" role="dialog" aria-labelledby="modalWelcomeTitle" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <section>
                        <div class="container mb-3">
                            <h2 class="text-secondary title-padding title-mobile">Report Post
                            </h2>
                            <hr>
                            <label class="col-md-8 control-label mx-2">Could you tell us the why:</label>
                            <select class="form-control mx-4" id="gender" name="gender" style="width:96.1%">
                                <option value="spam">It's spam</option>
                                <option value="copy">It infringes my copyright</option>
                                <option value="innapropriate">Inappropriate Content</option>
                                <option value="irrelevant">Irrelevant Content</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="row justify-content-end my-2 mx-1">
                                <button class="btn btn-secondary my-2 mr-1" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Close</button>
                                <button class="btn btn-danger my-2 ml-1" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Send
                                    Report</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Comment Modal -->
    <div class="modal" id="modalCommentReport" tabindex="-1" role="dialog" aria-labelledby="modalWelcomeTitle" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <section>
                        <div class="container mb-3">
                            <h2 class="text-secondary title-padding title-mobile">Report Comment
                            </h2>
                            <hr>
                            <label class="col-md-8 control-label mx-2">Could you tell us the why:</label>
                            <select class="form-control mx-4" id="gender" name="gender" style="width:96.1%">
                                <option value="spam">It's spam</option>
                                <option value="copy">It infringes my copyright</option>
                                <option value="innapropriate">Inappropriate Content</option>
                                <option value="irrelevant">Irrelevant Content</option>
                                <option value="other">Other</option>
                            </select>
                            <div class="row justify-content-end my-2 mx-1">

                                <button class="btn btn-secondary my-2 mr-1" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Close</button>
                                <button class="btn btn-danger my-2 ml-1" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Send
                                    Report</button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
<?php }

function draw_login_modals($auth)
{ ?>

    <!-- Modal -->
    <div class="modal" id="modalWelcome" tabindex="-1" role="dialog" aria-labelledby="modalWelcomeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-body login-modal">
                    <div class="col">

                        <div class="d-flex justify-content-center">
                            <img src="./images/pear_logo.png" width="auto" height="100" alt="logo">
                        </div>

                        <div>
                            <section>
                                <div class="container mb-3">
                                    <h2 class="text-center text-dark title-padding title-mobile">Welcome
                                    </h2>
                                    <div>
                                        <div class="d-flex justify-content-center">
                                            <button class="btn btn-secondary my-2" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin" style="box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">Continue
                                                with Email</button>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <a href="home.php?auth=<?= $auth ?>">
                                                <img width="172px" src="./images/google.png" alt="Google OAuth">
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
    </div>

    <!-- Modal -->
    <div class="modal" id="modalLogin" tabindex="-1" role="dialog" aria-labelledby="modalLoginTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg w-75" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <section>
                        <div class="row login-modal">
                            <div class="col-md-2 modal-image container pl-3 pt-8 m-0">
                                <!-- <img src="./images/pear_logo.png" style="width: 350px; height: 500px; object-fit: cover" alt="logo"> -->
                            </div>
                            <div class="col-md-8 my-auto mx-auto">
                                <div class="my-auto">
                                    <div class="container">
                                        <h2 class="text-center text-dark title-padding title-mobile mb-4">Log in
                                        </h2>
                                    </div>
                                    <div class="row d-flex justify-content-lg-center">
                                        <div class="col-lg-10 mb-4">
                                            <label for="emailAddress">Email</label>
                                            <input type="email" class="form-control" id="emailAddress" placeholder="Email" required>
                                        </div>
                                        <div class="col-lg-10 mty-3">
                                            <label for="pass">Password</label>
                                            <input type="password" id="pass" class="form-control" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}" placeholder="Password" required>
                                            <div class="invalid-feedback">
                                                Required!
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <div class="row d-flex align-items-center mr-2">
                                                <p class="m-0">Don't have an account? <a class="mr-1 text-center" href="">
                                                        <p class="font-weight-bold m-0" data-toggle="modal" data-dismiss="modal" data-target="#modalSignup">Sign up</p>
                                                    </a></p>
                                            </div>
                                            <a href="home.php?auth=<?= $auth ?>">
                                                <button type="submit" class="btn btn-outline-dark ">Log in</button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Missing: First Name, Last Name, birthday, gender -->
    <div class="modal" id="modalSignup" tabindex="-1" role="dialog" aria-labelledby="modalSignupTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <section>
                        <div class="row login-modal">
                            <div class="col-md-2 modal-image container pl-3 pt-8 m-0">
                            </div>
                            <div class="col-md-8 my-auto mx-auto">
                                <div class="my-auto">
                                    <div class="container">
                                        <h2 class="text-center text-dark title-padding title-mobile mb-4">Sign up
                                        </h2>
                                    </div>
                                    <div class="row d-flex justify-content-center">
                                        <div class="row d-flex justify-content-lg-center mb-2">
                                            <div class="col-lg-5 pr-0">
                                                <label for="nameInput">Full Name *</label>
                                                <input type="text" class="form-control" id="nameInput" placeholder="Full Name">
                                            </div>
                                            <div class="col-lg-5 pl-0">
                                                <label for="usernameInput">Username *</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">@</div>
                                                    </div>
                                                    <input type="text" class="form-control" id="usernameInput" placeholder="Username">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-lg-center">
                                            <div class="col-lg-10 p-0 mb-2">
                                                <label for="emailAddress">Email *</label>
                                                <input type="email" class="form-control" id="emailAddress" placeholder="Email" required>
                                            </div>
                                            <div class="col-lg-10 mb-2 p-0">
                                                <label for="pass">Password *</label>
                                                <input type="password" id="pass" class="form-control" aria-describedby="passwordHelp" pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}" placeholder="Password" required>
                                                <div class="invalid-feedback">
                                                    Required!
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4">
                                            <div class="row d-flex align-items-center mr-2">
                                                <p class="m-0">Already have an account? <a class="mr-1 text-center" href="">
                                                        <p class="font-weight-bold m-0" data-toggle="modal" data-dismiss="modal" data-target="#modalLogin">Log in</p>
                                                    </a>
                                                </p>
                                            </div>
                                            <a href="home.php?auth=<?= $auth ?>">
                                                <button type="submit" class="btn btn-outline-dark">Sign up</button>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

<?php
}

function vote_content($up_votes, $down_votes)
{ ?>
    <div class="d-flex align-items-end justify-content-end">
        <div class="col-2">
            <div class="d-flex justify-content-center pb-2">
                <a>
                    <p class="mb-0"> <?= $up_votes ?> </p>
                </a>
            </div>
            <div class="d-flex justify-content-center">
                <a>
                    <i class="fas fa-chevron-up fa-lg pb-2"></i>
                </a>
            </div>
            <div class="d-flex justify-content-center">
                <a>
                    <i class="fas fa-chevron-down fa-lg pb-2"></i>
                </a>
            </div>
            <div class="d-flex justify-content-center">
                <a>
                    <p> <?= $down_votes ?> </p>
                </a>
            </div>
        </div>
    </div>

<?php }

function profile_post_user($auth, $poster_name, $poster_page, $community_name, $up_votes, $down_votes, $image, $title, $content)
{ ?>

    <!-- Post -->
    <div class="card mb-4 post-container">

        <h6 class="card-header"><i class="fa fa-newspaper-o mr-1"></i>
            <a href="<?= $poster_page ?>?auth=<?= $auth ?>">@<?= $poster_name ?></a> <span class="text-muted"> posted on</span> <a href="community.php?auth=<?= $auth ?>">/<?= $community_name ?></a></h6>

        <div class="card-body pb-0 mx-2">
            <div class="row">

                <?php vote_content($up_votes, $down_votes); ?>

                <div class="col-md-10 mx-auto pb-3">
                    <a href="post.php?auth=<?= $auth ?>"> <img class="card-img-top thumbnail mr-2 my-0 pl-0 py-0 text-center" height="35" width="35" src=<?= $image ?> alt="Post Image" style="object-fit: contain;">
                        <?= $title ?></a>
                    <a href="post.php?auth=<?= $auth ?>">
                        <p class="card-text pt-2"><?= $content ?></p>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php }

function profile_post_myProfile($auth, $poster_name, $poster_page, $community_name, $image, $title, $content)
{ ?>

    <!-- Post -->
    <div class="card mb-4 post-container">

        <h6 class="card-header"><i class="fa fa-newspaper-o mr-1"></i>
            <a href="<?= $poster_page ?>?auth=<?= $auth ?>">@<?= $poster_name ?></a> <span class="text-muted"> posted on</span> <a href="community.php?auth=<?= $auth ?>">/<?= $community_name ?></a></h6>
        <a href="post.php?auth=<?= $auth ?>"> <img class="card-img-top thumbnail mr-2 mb-1 my-1" height="35" width="35" src=<?= $image ?> alt="Post Image">
            <?= $title ?></a>

        <div class="card-body post-thbn-body">
            <a href="post.php?auth=<?= $auth ?>">
                <p class="card-text"><?= $content ?></p>
            </a>
        </div>

    </div>

<?php }

function profile_comment_user($auth, $id, $commenter_name, $commenter_page, $up_votes, $down_votes, $post, $content)
{ ?>

    <!-- Comment -->
    <div id=<?= $id ?> class="card mb-4 post-container">

        <h6 class="card-header"><i class="fa fa-terminal mr-1"></i>
            <a href="<?= $commenter_page ?>?auth=<?= $auth ?>">@<?= $commenter_name ?></a> <span class="text-muted">commented on</span> <a href="<?= $post ?>?auth=<?= $auth ?>"><?= $post ?></a></h6>

        <div class="card-body pb-0 mx-2">
            <div class="row">

                <?php vote_content($up_votes, $down_votes); ?>

                <div class="col-md-10 mx-auto">
                    <a href="post.php?auth=<?= $auth ?>#<?= $id ?>">
                        <p class="card-text"><?= $content ?></p>
                    </a>
                </div>
            </div>
        </div>
    </div>

<?php }

function profile_comment_myProfile($auth, $id, $commenter_name, $commenter_page, $post, $content)
{ ?>

    <!-- Comment -->
    <div id=<?= $id ?> class="card mb-4 post-container">
        <h6 class="card-header"><i class="fa fa-terminal mr-1"></i>
            <a href="<?= $commenter_page ?>?auth=<?= $auth ?>">@<?= $commenter_name ?></a> <span class="text-muted">commented on</span> <a href="post.php?auth=<?= $auth ?>"><?= $post ?></a></h6>
        <div class="card-body">
            <a href="post.php?auth=<?= $auth ?>#<?= $id ?>">
                <p class="card-text"><?= $content ?></p>
            </a>
        </div>
    </div>

<?php }

function profile_info($auth, $points, $posts, $age, $gender)
{ ?>

    <div class="card my-4 aside-container">
        <h5 class="card-header aside-container-top" style="background-color: white;">
            Profile
        </h5>
        <div class="card-body">
            <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                <div class="col-1 pl-0">
                    <i class="fas fa-star"></i>
                </div>
                <div class="col">
                    <?= $points ?> points
                </div>
            </div>
            <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                <div class="col-1 pl-0">
                    <i class="fa fa-newspaper-o"></i>
                </div>
                <div class="col">
                    <?= $posts ?> posts
                </div>
            </div>

            <?php if ($auth === "true") { ?>
                <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                    <div class="col-1 pl-0">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <div class="col">
                        <?= $age ?> y.o.
                    </div>
                </div>
                <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                    <div class="col-1 pl-0">
                        <i class="fas fa-venus-mars"></i>
                    </div>
                    <div class="col">
                        <?= $gender ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

<?php }

function home_post($auth, $poster_name, $poster_page, $poster_image, $community, $date, $up_votes, $down_votes, $image, $title, $content)
{ ?>

    <!-- Post -->
    <div class="row no-gutters">
        <div class="col-md-11 pr-0 mr-0">
            <div class="card mb-4 post-container">
                <a href="post.php?auth=<?= $auth ?>">
                    <img class="card-img-top" style="padding: 15px 15px 0px;" src="<?= $image ?>" alt="Post Image">
                </a>
                <div style="padding: 0 12%">
                    <div class="row">

                        <?php vote_content($up_votes, $down_votes); ?>

                        <div class="col-md-10 mx-auto">
                            <a href="post.php?auth=<?= $auth ?>">
                                <div style="padding: 15px 0;">
                                    <h2 class="card-title"><?= $title ?></h2>
                                    <p class="card-text"><?= $content ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="card-footer row text-muted py-3  px-3" style="border-top: 1px solid rgb(76, 25, 27); background-color: white;">
                        <div class="col-md-5 align-self-center justify-content-start">
                            <div class="card-footer-buttons row align-content-center justify-content-start">
                                <a href="post.php?auth=<?= $auth ?>#new-comment-input"><i class="fas fa-reply"></i>Reply</a>
                                <a data-toggle="modal" data-dismiss="modal" data-target="#modalPostReport">
                                    <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row align-self-center justify-content-end">
                                <a href="<?= $poster_page ?>?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $poster_image ?> alt="Profile Image"></a>
                                <span class="px-1 align-self-center"><?= $date ?> by</span>
                                <a class="align-self-center" href="<?= $poster_page ?>?auth=<?= $auth ?>">@<?= $poster_name ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-1 px-0 ml-0">
            <div class="row">
                <div>
                    <a href="community.php?auth=<?= $auth ?>">
                        <h5 class="community-tag mb-0"><?= $community ?></h5>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php }

function post_comment($auth, $id, $commenter_name, $commenter_page, $commenter_image, $date, $up_votes, $down_votes, $content, $class)
{ ?>

    <!-- Comment -->
    <div id=<?= $id ?> class="card mb-2 post-container <?= $class ?>">
        <div class="row pt-4">

            <?php vote_content($up_votes, $down_votes); ?>

            <div class="col-md-10 mx-auto">
                <p class="card-text"><?= $content ?>
                </p>
            </div>
        </div>
        <div class="card-footer row text-muted p-3" style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
            <div class="col-md-6 align-self-center">
                <div class="card-footer-buttons row align-content-center justify-content-start">
                    <a href="post.php?auth=<?= $auth ?>#new-comment-input"><i class="fas fa-reply"></i>Reply</a>
                    <a data-toggle="modal" data-dismiss="modal" data-target="#modalCommentReport">
                        <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                    <a href="<?= $commenter_page ?>?auth=<?= $auth ?>"> <img height="35" width="35" src=<?= $commenter_image ?> alt="Profile Image"> </a>
                    <span class="px-1 align-self-center"><?= $date ?> by</span>
                    <a class="align-self-center" href="<?= $commenter_page ?>?auth=<?= $auth ?>">@<?= $commenter_name ?></a>
                </div>
            </div>
        </div>
    </div>

<?php }

function report_footer_comment_and_post($auth, $reporter_name, $reporter_name_page, $reporter_image, $date)
{ ?>

    <div class="card-footer row text-muted mx-0">
        <div class="col-md-6 align-self-center">
            <div class="card-footer-buttons row align-content-center justify-content-start">
                <a href="#"><i class="fas fa-trash-alt"></i>Delete</a>
                <a href="#"><i class="fas fa-ban"></i>Ban User</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-self-center justify-content-end">
                <a href="<?= $reporter_name_page ?>?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $reporter_image ?> alt="Profile Image"></a>
                <span class="px-1 align-self-center"><?= $date ?> by</span>
                <a class="align-self-center" href="<?= $reporter_name_page ?>?auth=<?= $auth ?>">@<?= $reporter_name ?></a>
            </div>
        </div>
    </div>

<?php }

function report_post($auth, $reporter_name, $reporter_name_page, $reporter_image, $date, $community_name, $community_image, $title, $content)
{ ?>

    <!-- Reported Post -->
    <div class="card mb-4 post-container">
        <div class="card-body">
            <a href="community.php?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $community_image ?> alt="Profile Image"> /<?= $community_name ?> </a>
            <a href="post.php?auth=<?= $auth ?>">
                <h5 class="mt-1"><?= $title ?></h5>
                <p class="card-text"><?= $content ?>
                </p>
            </a>
        </div> <?php report_footer_comment_and_post($auth, $reporter_name, $reporter_name_page, $reporter_image, $date); ?>
    </div>

<?php }

function report_comment($auth, $id, $reporter_name, $reporter_name_page, $reporter_image, $date, $post, $content)
{ ?>

    <!-- Reported Comment -->
    <div class="card mb-4 post-container">
        <div class="card-body">
            <a href="community.php?auth=<?= $auth ?>">
                <h5> <?= $post ?></h5>
            </a>
            <a href="post.php?auth=<?= $auth ?>#<?= $id ?>">
                <p class="card-text"><?= $content ?>
                </p>
            </a>
        </div>
        <?php report_footer_comment_and_post($auth, $reporter_name, $reporter_name_page, $reporter_image, $date); ?>
    </div>

<?php }

function report_user($auth, $reporter_name, $reporter_name_page, $reporter_image, $reported_name, $reported_page, $reported_image, $date, $content)
{ ?>

    <!-- Reported User -->
    <div class="card mb-4 post-container">
        <div class="card-body">
            <a href="<?= $reported_page ?>?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $reported_image ?> alt="Profile Image"> @<?= $reported_name ?> </a>
            <p class="card-text mt-2"> <i class="fas fa-exclamation-triangle mr-1"></i><?= $content ?></p>
        </div>
        <div class="card-footer row text-muted mx-0">
            <div class="col-md-6 align-self-center">
                <div class="card-footer-buttons row align-content-center justify-content-start">
                    <a href="#"><i class="fas fa-ban"></i>Ban User</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                    <a href="<?= $reporter_name_page ?>?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $reporter_image ?> alt="Profile Image"></a>
                    <span class="px-1 align-self-center"><?= $date ?> by</span>
                    <a class="align-self-center" href="<?= $reporter_name_page ?>?auth=<?= $auth ?>">@<?= $reporter_name ?></a>
                </div>
            </div>
        </div>
    </div>

<?php }

function report_community($auth, $reporter_name, $reporter_name_page, $reporter_image, $community_name, $community_image, $date, $content)
{ ?>

    <!-- Reported Community -->
    <div class="card mb-4 post-container">
        <div class="card-body">
            <a href="community.php?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $community_image ?> alt="Profile Image"> /<?= $community_name ?> </a>
            <p class="card-text mt-2"> <i class="fas fa-exclamation-triangle mr-1"></i><?= $content ?></p>
        </div>
        <div class="card-footer row text-muted mx-0">
            <div class="col-md-6 align-self-center">
                <div class="card-footer-buttons row align-content-center justify-content-start">
                    <a href="#"><i class="fas fa-trash-alt"></i>Delete</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                    <a href="<?= $reporter_name_page ?>?auth=<?= $auth ?>"><img height="35" width="35" src=<?= $reporter_image ?> alt="Profile Image"></a>
                    <span class="px-1 align-self-center"><?= $date ?> by</span>
                    <a class="align-self-center" href="<?= $reporter_name_page ?>?auth=<?= $auth ?>">@<?= $reporter_name ?></a>
                </div>
            </div>
        </div>
    </div>

<?php } ?>