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
                <img class="rounded-circle profile-pic" src="./images/avatar_male.png" alt="Profile Image">
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
                            <a href="myProfile.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                                <div class="nav-border">Activity</div>
                            </a>
                            <div class="nav-border-active" style="border-bottom: 0px;">Communities</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Post Column -->
        <div class="col-md-9">

            <h1 class="col-md-4.5 my-4 username-header">@someusername</h1>

            <!-- Community Thumbnail -->
            <div id="c1" class="card mb-4 post-container">
                <div class="card-body community-thumbnail">
                    <div class="community-thumbnail-row">
                        <div>
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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
                            <a href="community.php?auth=<?= $auth ?>&admin=<?= $admin ?>"> <img
                                    class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                                    src="./images/Porto.jpg" alt="Post Image">
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