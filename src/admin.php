<?php

include('common.php');
draw_head('Admin');

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "true";
}

draw_navigation($auth, "", "true");

?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Aside -->
        <div class="col-md-3 aside" style="padding: 20 0;">

            <!-- My Categories -->
            <div class="card aside-container sticky-top">
                <h5 class="card-header aside-container-top" style="border: 1px solid rgba(76, 25, 27); border-radius: 2px; background-color: rgb(76, 25, 27);">
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <a href="#">
                                <div class="nav-border-active">
                                    All
                                </div>
                            </a>
                            <a href="#">
                                <div class="nav-border">Users</div>
                            </a>
                            <a href="#">
                                <div class="nav-border">Comments</div>
                            </a>
                            <a href="#">
                                <div class="nav-border">Posts</div>
                            </a>
                            <a href="#">
                                <div class="nav-border" style="border-bottom: 0px;">Communities</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Reports Column -->
        <div class="col-md-9">

            <h1 class="mt-3 mb-4 ml-2"> All Requests</h1>

            <!-- Reported Post -->
            <?php report_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", "Porto", "./images/Porto.jpg", "Financial help", "So I have had some offers from my university so now I'm looking at
                            financing and asked my parents if they would help with living costs so I could focus on
                            my studies. What do I do. Will a part time job be able to support all of my living
                            costs?"); ?>

            <!-- Reported Comment -->
            <?php report_comment($auth, "c1", "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", "Post1", "It is Computer Science but yeah my main issue is maths i tried going to every
                            election - writing down every single thing (even if i don't understand a single thing) and then
                            going home realizing that i cant understand anything. I may be low on IQ or something i dont
                            know."); ?>

            <!-- Reported User -->
            <?php report_user($auth, "someotherusername", "userProfile.php", "./images/avatar_female.png", "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", "Always sharing
                        incorrect informatiom!") ?>

            <!-- Reported Community -->
            <?php report_community($auth, "someotherusername", "userProfile.php", "./images/avatar_female.png", "Porto", "./images/Porto.jpg", "March 5, 2020", "Duplicated Community!") ?>

        </div>

    </div>

</div>

<?php
draw_footer($auth);

?>