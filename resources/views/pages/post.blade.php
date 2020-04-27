
<!-- Page Content -->
<div class="container">

    <!-- Posts Column -->
    <div class="col-md-12">

        <div class="container">
            <div class="row">
                <div class="col-md-2 text-center community-pic-container">
                    <img class="community-pic" src="{{ asset('img/Porto.jpg') }}" alt="Community Image">
                </div>
                <div class="col-md-7">
                    <a href="community.php?auth=<?= $auth ?>">
                        <h1 class="my-5">/Porto</h1>
                    </a>
                </div>
            </div>
        </div>

        <!-- Post -->
        <div class="card mb-4 post-container">
            <img class="card-img-top pl-5 pr-5 pt-5 pb-2 m-0"
                src="https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg" alt="Post Image">

            <div style="padding: 0 12%">

                <div class="row">

                    <?php vote_content(12, 2); ?>

                    <div class="col-md-10 mx-auto">
                        <div style="padding-top: 15px;">
                            <h2 class="card-title">Problem with studying.</h2>
                            <p class="card-text post-body">
                                Hello i am desperately trying to find a way to learn how to learn. I am
                                in the first semester of my CS uni and i just realised that i dont know how to start
                                learning a new course. I tried reading the provided book / searching on
                                internet but when it comes to the homework i dont know a single thing...So please if you
                                have any umm i dont know tip or how to it would be nice.
                                <p>
                                    Thank you.
                                </p>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer row text-muted p-3"
                    style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
                    <div class="col-md-6 align-self-center ">
                        <div class="card-footer-buttons row align-content-center justify-content-start">
                            <?php if ($admin === "false") { ?>
                            <a href="/post.php?auth=<?= $auth ?>#new-comment-input"><i
                                    class="fas fa-reply"></i>Reply</a>
                            <a data-toggle="modal" data-dismiss="modal" data-target="#modalPostReport">
                                <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                            </a>
                            <? } else { ?>
                            <a href="#"><i class="fas fa-trash-alt"></i>Delete</a>
                            <? } ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row align-self-center justify-content-end">
                            <a href="myProfile.php?auth=<?= $auth ?>"><img height="35" width="35"
                                    src="./images/avatar_male.png" alt="Profile Image"></a>
                            <span class="px-1 align-self-center">March 5, 2020 by</span>
                            <a class="align-self-center" href="myProfile.php?auth=<?= $auth ?>&admin=<?= $admin ?>">
                                @someusername</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($admin === "false") { ?>
        <!-- Add Comment -->
        <div class="card post-container" id="new-comment-input">
            <div class="card-body">
                <div class="row" style="font-size: 0.45rem;">
                    <div class="col-md-10 pr-md-0">
                        <!-- <input type="text" class="form-control" placeholder="New Comment"> -->
                        <textarea rows="1" onclick="this.rows = '8';" onblur="if(this.value == '') this.rows = '1';"
                            type="text" class="form-control mr-0" placeholder="New Comment"></textarea>
                    </div>
                    <!--<div class="col-md-1 my-auto mx-auto text-right">-->
                    <div class="col-md-1 my-auto mx-auto text-right px-0 text-center comment-button">
                        <button type="button" class="btn btn-md btn-dark"> Add</button>
                    </div>
                </div>
            </div>
        </div>
        <? } ?>

        <!-- Comment -->
        <?php post_comment($auth, "c1", "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", 12, 2, "Personally, it depends what you are studying but for
                        me, if it is a subject
                        that has math / physics / formula then I find that just practicing questions (normally
                        in your textbook) is the best way to learn.", "post-comment", $admin) ?>

        <!-- Comment Reply -->
        <?php post_comment($auth, "c1", "someotherusername", "userProfile.php", "./images/avatar_female.png", "March 5, 2020", 12, 2, "It is Computer Science but yeah my main issue is maths i tried going to
                        every election - writing down every single thing (even if i don't understand a single thing)
                        and then going home realizing that i cant understand anything. I may be low on IQ or something i dont know.
                        Thanks for the reply", "post-reply", $admin) ?>

        <!-- Comment -->
        <?php post_comment($auth, "c2", "someusername", "myProfile.php", "./images/avatar_male.png", "March 5, 2020", 34, 2, "It can be a bit overwhelming at first, but there's definitely a system to
                        learn effectively. And that is regardless of the course and the amount of work.", "post-comment", $admin) ?>

    </div>
</div>
