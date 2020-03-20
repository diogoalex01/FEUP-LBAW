<?php

include('common.php');
draw_head('Search Results');

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "false";
}

draw_navigation($auth, "studying");

?>

<!-- Page Content -->
<div class="container">

    <div class="row" style="padding: 20 0;">

        <!-- Aside -->
        <div class="col-md-3 aside ">
            <!-- My Categories -->
            <div class="card aside-container sticky-top">
                <h5 class="card-header aside-container-top" style="border: 1px solid rgba(76, 25, 27); border-radius: 2px; background-color: rgb(76, 25, 27);">
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <a href="#">
                                <div class="nav-border-active">
                                    Home
                                </div>
                            </a>
                            <a href="#">
                                <div class="nav-border">Popular</div>
                            </a>
                            <a href="#">
                                <div class="nav-border">Trending</div>
                            </a>
                            <a href="#">
                                <div class="nav-border" style="border-bottom: 0px;">Universities</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <!-- Post -->
            <?php home_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", 12, 2, "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Problem with studying.", "Hello i am desperately trying to find a way
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
                                        Thank you."); ?>
        </div>
    </div>
</div>

<?php
draw_footer($auth);

?>