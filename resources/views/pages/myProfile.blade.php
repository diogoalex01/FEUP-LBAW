<?php

include('common.php');
draw_head("My Profile");

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "false";
}

$admin = $_GET['admin'];
if (!isset($_GET['admin'])) {
    $admin = "false";
}

draw_navigation($auth, "", $admin);
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Aside -->
        <div class="col-md-3 aside profile-aside">

            <div class="profile-pic-container text-center">
                <img class="rounded-circle profile-pic " src="./images/avatar_male.png" alt="Profile Image">
            </div>

            <div class="row">
                <?php if ($admin === "true") { ?>
                <div class="text-center mx-auto">
                    <input type="button" class="btn btn-outline-danger" value="Ban User">
                </div>
                <? } ?>
            </div>

            <?php profile_info($auth, 25, 4, 27, "Male") ?>

            <!-- My Categories -->
            <div class="card aside-container">
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <div class="nav-border-active">Activity</div>

                            <a href="myCommunities.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                                <div class="nav-border" style="border-bottom: 0px;">Communities</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <h1 class="my-4 username-header">@someusername</h1>

            <!-- New Post -->
            <a href="newPost.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                <div class="card mb-4 post-container">
                    <div class="card-body">
                        <div class="row" style="font-size: 0.45rem;">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Write your own article">
                            </div>
                            <div class="col-1 pl-0 my-auto">
                                <i class="fas fa-plus-circle fa-4x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Post -->

            <?php profile_post_myProfile($auth, "someusername", "myProfile.php", "Porto", "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Post 1", "Personally, it depends what you are studying but for me, if it is a
                                        subject that has math / physics / formula then I find that just practicing questions
                                        (normally in your textbook) is the best way to learn.", $admin); ?>

            <!-- Comment -->
            <?php profile_comment_myProfile($auth, "c1", "someusername", "myProfile.php", "Post 1", "Personally, it depends what you are studying but for me, if it is a
                                        subject that has math / physics / formula then I find that just practicing questions
                                        (normally in your textbook) is the best way to learn.", $admin); ?>

            <!-- Post -->
            <?php profile_post_myProfile($auth, "someusername", "myProfile.php", "Porto", "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Post 2", "Preferably free or a cheap fee but as I write my analysis essay, I was
                                wondering if there are good tools for editing papers? If so, which do you use?", $admin); ?>

            <!-- Comment -->
            <?php profile_comment_myProfile($auth, "c2", "someusername", "myProfile.php", "Post 4", "It can be a bit overwhelming at first, but there's definitely a system
                                to learn effectively. And that is regardless of the course and the amount of work.", $admin); ?>

            <!-- Post -->
            <?php profile_post_myProfile($auth, "someusername", "myProfile.php", "Porto", "./images/Porto.jpg", "Financial help", "So I have had some offers from my university so now I'm looking at
                                financing and asked my parents if they would help with living costs so I could focus on
                                my studies. What do I do. Will a part time job be able to support all of my living
                                costs?", $admin); ?>

            <!-- Post -->
            <?php profile_post_myProfile($auth, "someusername", "myProfile.php", "Porto", "./images/UPorto.png", "University acceptance", "I just got accepted to UMass Amherst as an international
                                undergraduate(freshman for engn). Any tips that might help me start well my year ?", $admin); ?>

        </div>
    </div>
</div>
<?
draw_footer($auth, $admin);
?>