<?php

include('common_tpl.php');

draw_header();
draw_navigation();
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Aside -->
        <div class="col-md-3 aside">
            <!-- My Categories -->
            <div class="card my-4 aside-container">
                <h5 class="card-header aside-container-top">My Categories</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <ul class="list-unstyled mb-0">
                                <li> <a href="#">UPorto</a></li>
                                <li> <a href="#">FEUP</a></li>
                                <li> <a href="#">MIEIC</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Categories -->
            <div class="card my-4  aside-container">
                <h5 class="card-header aside-container-top">Top Communities</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <ul class="list-unstyled mb-0">
                                <li> <a href="community.html">Porto</a></li>
                                <li> <a href="#">Homework</a></li>
                                <li> <a href="#">Applications</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Posts Column -->
        <div class="col-md-9">

            <h1 class="my-4">My Feed</h1>

            <!-- Post -->
            <div class="card mb-4 post-container">
                <a href="post.html">
                    <img class="card-img-top p-2" src="./images/ICBAS.png" alt="Post Image">
                    <div class="card-body">
                        <h2 class="card-title">Post 1</h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis
                            aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis
                            animi
                            vero voluptate voluptatibus possimus, veniam magni quis!</p>
                    </div>
                </a>
                <div class="card-footer text-muted">
                    <a><i class="fas fa-chevron-up"></i></a>
                    <a><i class="fas fa-chevron-down"></i></a>
                    <span class="card-footer-buttons">
                        <a href="post.html"><i class="fas fa-reply"></i>Reply</a>
                        <a href="#"><i class="fas fa-flag"></i></i>Report</a>
                    </span>
                    <a href="myProfile.html"><img height="35" width="35" src="./images/avatar_male.png" alt="Profile Image"></a>
                    Posted on March 5, 2020 by
                    <a href="myProfile.html">@someusername</a>
                </div>
            </div>

            <!-- Post -->
            <div class="card mb-4 post-container">
                <a href="post.html">
                    <img class="card-img-top p-2" src="./images/Porto.jpg" alt="Post Image">
                    <div class="card-body">
                        <h2 class="card-title">Post 2</h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis
                            aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis
                            animi
                            vero voluptate voluptatibus possimus, veniam magni quis!</p>
                    </div>
                </a>
                <div class="card-footer text-muted">
                    <a><i class="fas fa-chevron-up"></i></a>
                    <a><i class="fas fa-chevron-down"></i></a>
                    <span class="card-footer-buttons">
                        <a href="post.html"><i class="fas fa-reply"></i>Reply</a>
                        <a href="#"><i class="fas fa-flag"></i></i>Report</a>
                    </span>
                    <a href="myProfile.html"><img height="35" width="35" src="./images/avatar_male.png" alt="Profile Image"></a>
                    Posted on March 5, 2020 by
                    <a href="myProfile.html">@someusername</a>
                </div>
            </div>

            <!-- Post -->
            <div class="card mb-4 post-container">
                <a href="post.html">
                    <img class="card-img-top p-2" src="./images/Porto.jpg" alt="post1 image">
                    <div class="card-body">

                        <h2 class="card-title">Post 3</h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis
                            aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis
                            animi
                            vero voluptate voluptatibus possimus, veniam magni quis!</p>
                    </div>
                </a>
                <div class="card-footer text-muted">
                    <a><i class="fas fa-chevron-up"></i></a>
                    <a><i class="fas fa-chevron-down"></i></a>
                    <span class="card-footer-buttons">
                        <a href="post.html"><i class="fas fa-reply"></i>Reply</a>
                        <a href="#"><i class="fas fa-flag"></i></i>Report</a>
                    </span>
                    <a href="myProfile.html"><img height="35" width="35" src="./images/avatar_male.png" alt="Profile Image"></a>
                    Posted on March 5, 2020 by
                    <a href="myProfile.html">@someusername</a>
                </div>
            </div>

            <!-- Post -->
            <div class="card mb-4 post-container">
                <a href="post.html">
                    <img class="card-img-top p-2" src="./images/UPorto.png" alt="Post Image">
                    <div class="card-body">

                        <h2 class="card-title">Post 4</h2>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis
                            aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis
                            animi
                            vero voluptate voluptatibus possimus, veniam magni quis!</p>
                    </div>
                </a>
                <div class="card-footer text-muted">
                    <a><i class="fas fa-chevron-up"></i></a>
                    <a><i class="fas fa-chevron-down"></i></a>
                    <span class="card-footer-buttons">
                        <a href="post.html"><i class="fas fa-reply"></i>Reply</a>
                        <a href="#"><i class="fas fa-flag"></i></i>Report</a>
                    </span>
                    <a href="myProfile.html"><img height="35" width="35" src="./images/avatar_male.png" alt="Profile Image"></a>
                    Posted on March 5, 2020 by
                    <a href="myProfile.html">@someusername</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
draw_footer();

?>