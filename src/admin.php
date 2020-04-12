<?php

include('common.php');
draw_head('Admin');

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "true";
}

$admin = $_GET['admin'];
if (!isset($_GET['admin'])) {
    $admin = "true";
}

draw_navigation($auth, "", $admin);

?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Aside -->
        <div class="col-md-3 aside" style="padding-top:20px;">

            <!-- My Categories -->
            <div class="card aside-container sticky-top">
                <h5 class="card-header aside-container-top" style="border: 1px solid rgba(76, 25, 27); border-radius: 2px; background-color: rgb(76, 25, 27);">
                </h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <div id="all_menu" class="admin-aside nav-border-active">All</div>
                            <div id="users_menu" class="admin-aside nav-border">Users</div>
                            <div id="comments_menu" class="admin-aside nav-border">Comments</div>
                            <div id="posts_menu" class="admin-aside nav-border">Posts</div>
                            <div id="communities_menu" class="admin-aside nav-border" style="border-bottom: 0px;">Communities</div>
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
                            costs?", $admin); ?>

            <!-- Reported Comment -->
            <?php report_comment($auth, "c1", "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", "Post1", "It is Computer Science but yeah my main issue is maths i tried going to every
                            election - writing down every single thing (even if i don't understand a single thing) and then
                            going home realizing that i cant understand anything. I may be low on IQ or something i dont
                            know.", $admin); ?>

            <!-- Reported User -->
            <?php report_user($auth, "someotherusername", "userProfile.php", "./images/avatar_female.png", "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", "Always sharing
                        incorrect informatiom!", $admin); ?>

            <!-- Reported Community -->
            <?php report_community($auth, "someotherusername", "userProfile.php", "./images/avatar_female.png", "Porto", "./images/Porto.jpg", "March 5, 2020", "Duplicated Community!", $admin); ?>

        </div>

    </div>

</div>

<?php
draw_footer($auth, $admin);
?>

<script>
    let admin_aside = document.querySelectorAll(".admin-aside")
    let all_rep = document.querySelectorAll(".report")

    let all_menu = document.getElementById("all_menu")
    all_menu.addEventListener("click", function() {
        for (let i = 0; i < admin_aside.length; i++) {
            admin_aside[i].setAttribute("class", "nav-border")
        }
        all_menu.setAttribute("class", "nav-border-active")

        for (let i = 0; i < all_rep.length; i++) {
            if (all_rep[i].style.display === "none") {
                all_rep[i].style.display = "block"
            }
        }
    })

    let users_menu = document.getElementById("users_menu")
    users_menu.addEventListener("click", function() {
        for (let i = 0; i < admin_aside.length; i++) {
            if (admin_aside[i].hasAttribute("nav-border-active"))
                admin_aside[i].removeAttribute("class", "nav-border-active")
            admin_aside[i].setAttribute("class", "nav-border")
        }
        users_menu.setAttribute("class", "nav-border-active")

        for (let i = 0; i < all_rep.length; i++) {
            if (all_rep[i].classList.contains("user-report")) {
                all_rep[i].style.display = "block"
            } else {
                all_rep[i].style.display = "none"
            }
        }
    })

    let comments_menu = document.getElementById("comments_menu")
    comments_menu.addEventListener("click", function() {
        for (let i = 0; i < admin_aside.length; i++) {
            if (admin_aside[i].hasAttribute("nav-border-active"))
                admin_aside[i].removeAttribute("class", "nav-border-active")
            admin_aside[i].setAttribute("class", "nav-border")
        }
        comments_menu.setAttribute("class", "nav-border-active")

        for (let i = 0; i < all_rep.length; i++) {
            if (all_rep[i].classList.contains("comment-report")) {
                all_rep[i].style.display = "block"
            } else {
                all_rep[i].style.display = "none"
            }
        }
    })

    let posts_menu = document.getElementById("posts_menu")
    posts_menu.addEventListener("click", function() {
        for (let i = 0; i < admin_aside.length; i++) {
            if (admin_aside[i].hasAttribute("nav-border-active"))
                admin_aside[i].removeAttribute("class", "nav-border-active")
            admin_aside[i].setAttribute("class", "nav-border")
        }
        posts_menu.setAttribute("class", "nav-border-active")

        for (let i = 0; i < all_rep.length; i++) {
            if (all_rep[i].classList.contains("post-report")) {
                all_rep[i].style.display = "block"
            } else {
                all_rep[i].style.display = "none"
            }
        }
    })

    let communities_menu = document.getElementById("communities_menu")
    communities_menu.addEventListener("click", function() {
        for (let i = 0; i < admin_aside.length; i++) {
            if (admin_aside[i].hasAttribute("nav-border-active"))
                admin_aside[i].removeAttribute("class", "nav-border-active")
            admin_aside[i].setAttribute("class", "nav-border")
        }
        communities_menu.setAttribute("class", "nav-border-active")

        for (let i = 0; i < all_rep.length; i++) {
            if (all_rep[i].classList.contains("community-report")) {
                all_rep[i].style.display = "block"
            } else {
                all_rep[i].style.display = "none"
            }
        }
    })
</script>