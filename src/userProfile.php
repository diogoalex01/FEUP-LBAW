<?php

include('common.php');
draw_head("User Profile");

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "false";
}

draw_navigation($auth);
?>
<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Aside -->
        <div class="col-md-3 aside profile-aside">

            <!-- <div class="container"> -->
            <div class="profile-pic-container text-center">
                <img class="rounded-circle profile-pic " src="./images/avatar_female.png" alt="Profile Image">
            </div>
            <!-- </div> -->
            <?php if ($auth === "true") { ?>
                <div class="row">
                    <div class="col-6 text-center d-flex align-items-center justify-content-end">
                        <input type="button" class="btn btn-outline-danger" value="Block">
                    </div>
                    <div class="col-1 text-center d-flex align-items-center ">
                        <input type="button" class="btn btn-dark" value="Follow">
                    </div>
                </div>
            <? } ?>

            <?php profile_info($auth, 3353, 4, 23, "Female") ?>

            <?php if ($auth === "true") { ?>
                <!-- My Categories -->
                <div class="card aside-container">
                    <div class="card-body">
                        <div class="row">
                            <div class="col justify-content-start">
                                <div class="nav-border-active">Activity</div>

                                <a href="userCommunities.php?auth=<?= $auth ?>">
                                    <div class="nav-border" style="border-bottom: 0px;">Communities</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <div class="container">
                <h1 class="col-md-4.5 my-4 username-header">@someotherusername</h1>
            </div>

            <?php if ($auth === "true") { ?>

                <!-- Post -->
                <?php profile_post_user($auth, "someusername", "myProfile.php", "Porto", 20, 5, "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Post 1", "Personally, it depends what you are studying but for me, if it is a
                                        subject that has math / physics / formula then I find that just practicing questions
                                        (normally in your textbook) is the best way to learn."); ?>

                <!-- Comment -->
                <?php profile_comment_user($auth, "c1", "someusername", "myProfile.php", 12, 2, "Post 1", "Personally, it depends what you are studying but for me, if it is a
                                        subject that has math / physics / formula then I find that just practicing questions
                                        (normally in your textbook) is the best way to learn.") ?>

                <!-- Post -->
                <?php profile_post_user($auth, "someusername", "myProfile.php", "Porto", 13, 5, "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Post 2", "Preferably free or a cheap fee but as I write my analysis essay, I was
                                wondering if there are good tools for editing papers? If so, which do you use?"); ?>

                <!-- Comment -->
                <?php profile_comment_user($auth, "c2", "someusername", "myProfile.php", 43, 2, "Post 4", "It can be a bit overwhelming at first, but there's definitely a system
                                to learn effectively. And that is regardless of the course and the amount of work.") ?>

                <!-- Post -->
                <?php profile_post_user($auth, "someusername", "myProfile.php", "Porto", 22, 35, "./images/Porto.jpg", "Financial help", "So I have had some offers from my university so now I'm looking at
                                financing and asked my parents if they would help with living costs so I could focus on
                                my studies. What do I do. Will a part time job be able to support all of my living
                                costs?"); ?>

                <!-- Post -->
                <?php profile_post_user($auth, "someusername", "myProfile.php", "Porto", 20, 3, "./images/UPorto.png", "University acceptance", "I just got accepted to UMass Amherst as an international
                                undergraduate(freshman for engn). Any tips that might help me start well my year ?"); ?>

            <?php } else { ?>
                <!-- Private notice -->
                <div class="card mb-4 post-container">

                    <h5 class="card-header aside-container-top d-flex align-items-center">

                        <div class="col-1 pr-lg-0">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <div class="col pl-lg-0">
                            This Account is Private
                        </div>

                    </h5>

                    <div class="card-body justify-content-start">
                        <p class="card-text">Follow to see their private content.</p>
                    </div>

                </div>
            <?php } ?>
        </div>
    </div>
</div>


<?
draw_footer($auth);
?>