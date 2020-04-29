<?php

include('common.php');
draw_head("User Profile");

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

            <!-- <div class="container"> -->
            <div class="profile-pic-container text-center">
                <img class="rounded-circle profile-pic " src="./images/avatar_female.png" alt="Profile Image">
            </div>
            <!-- </div> -->

            <div class="row">
                <?php if ($admin === "false") { ?>
                <div class="col-6 text-center d-flex align-items-center justify-content-end">
                    <input type="button" class="btn btn-outline-danger" value="Block">
                </div>
                <div class="col-1 text-center d-flex align-items-center ">
                    <input type="button" class="btn btn-dark" value="Follow">
                </div>
                <? } else { ?>
                <div class="text-center mx-auto">
                    <input type="button" class="btn btn-outline-danger" value="Ban User">
                </div>
                <? } ?>
            </div>

            <?php profile_info($auth, 3353, 4, 23, "Female") ?>

            <!-- My Categories -->
            <div class="card aside-container">
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <a href="userProfile.php?auth=<?= $auth ?>">
                                <div class="nav-border" style="border-bottom: 0px;">Activity</div>
                            </a>
                            <div class="nav-border-active">Communities</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Post Column -->
        <div class="col-md-9">

            <h1 class="col-md-4.5 my-4 username-header">@someotherusername</h1>

            <!-- Community Thumbnail -->
            <div id="c1" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>">
                                <img class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c2" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c3" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c4" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c5" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c6" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c7" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c8" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Community Thumbnail -->
            <div id="c9" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>"> <img class="card-img-top card-img thumbnail mr-2 mb-1"
                                    height="35" width="35" src="./images/Porto.jpg" alt="Post Image">
                                /Porto</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<?
draw_footer($auth, $admin);
?>