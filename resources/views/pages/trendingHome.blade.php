<?php

include('common.php');
draw_head('Home');

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

    <div class="row" style="padding: 20 0;">

        <!-- Aside -->
        <div class="col-md-3 aside ">

            <!-- My Categories -->
            <div class="card aside-container sticky-top">
                <h5 class="card-header aside-container-top"
                    style="border: 1px solid rgba(76, 25, 27); border-radius: 2px; background-color: rgb(76, 25, 27);">
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <a href="home.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                                <div class="nav-border">
                                    Home
                                </div>
                            </a>
                            <a href="popularHome.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                                <div class="nav-border">Popular</div>
                            </a>
                            <div class="nav-border-active">Trending</div>

                            <a href="universitiesHome.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                                <div class="nav-border" style="border-bottom: 0px;">Universities</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <?php if ($auth === "true") { ?>
            <!-- New Post -->
            <a href="newPost.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                <div class="mt-4 mt-md-0 card mb-4 post-container">
                    <div class="card-body">
                        <div class="row" style="font-size: 0.45rem;">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Write your own post">
                            </div>
                            <div class="col-1 pl-0 my-auto">
                                <i class="fas fa-plus-circle fa-4x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php } ?>

            <!-- Post -->
            <?php home_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "/Porto", "March 5, 2020", 12, 2, "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Problem with studying.", "Hello i am desperately trying to find a way
                                        to learn how to learn. I am
                                        in the first semester of my CS uni and i just realised that i dont know how
                                        to
                                        start
                                        learning a new course. I tried reading the provided book / searching on
                                        internet
                                        but
                                        when it comes to the homework i dont know a single thing...So please if you
                                        have
                                        any
                                        umm
                                        i dont know tip or how to it would be nice. <br>
                                        Thank you.", $admin); ?>

            <!-- Post -->
            <?php home_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "/Porto", "March 5, 2020", 12, 2, "https://cdn.thecollegeinvestor.com/wp-content/uploads/2018/03/WP_FORGIVE.jpg", "Financial help", "So I have had some offers from my university so now I'm
                                        looking at
                                        financing and asked my parents if they would help with living costs so I
                                        could focus on
                                        my studies. What do I do. Will a part time job be able to support all of my
                                        living
                                        costs?", $admin); ?>

            <!-- Post -->
            <?php home_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "/Porto", "March 5, 2020", 15, 2, "https://image.freepik.com/free-photo/vintage-typewriter-header-retro-machine-technology_1484-1355.jpg", "What editor do you people use for papers?", "Preferably free or a cheap fee but as I write my analysis
                                        essay, I was
                                        wondering if there are good tools for editing papers? If so, which do you
                                        use?", $admin); ?>

            <!-- Post -->
            <?php home_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "/Porto", "March 5, 2020", 12, 2, "https://static.wixstatic.com/media/969f6d_76c95d0987e2442799573d290138b124~mv2.jpg", "University acceptance", "I just got accepted to UMass Amherst as an international
                                        undergraduate(freshman for engn). Any tips that might help me start well my
                                        year ?", $admin); ?>

        </div>
    </div>
</div>

<?php
draw_footer($auth, $admin);

?>