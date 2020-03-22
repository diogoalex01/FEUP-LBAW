<?php

include('common.php');
draw_head('New Post');

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "false";
}

draw_navigation($auth);

?>

<!-- Page Content -->
<div class="container">

    <!-- Posts Column -->
    <div class="col-md-14">
        <h1 class="my-4 text-center">New Post <i class="fa fa-keyboard-o" aria-hidden="true"></i></h1>
        <hr>
        <!-- New Post Input -->
        <div class="card mb-4 post-container">
            <div class="card-body ">
                <form action="home.php" method ="get">
                    <input type="text" class="form-control mb-2" placeholder="/exampleCommunity">
                    <input name="auth" hidden value="<?= $auth ?>">
                    <input type="text" class="form-control" placeholder="Example Title">
                    <div class="row">
                        <p class="m-3 font-weight-bold">Set Privacy</p>
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitches">
                                <label class="custom-control-label" for="customSwitches">Private Account</label>
                            </div>
                        </div>
                    </div>
                    <textarea type="text" class="form-control mb-3" placeholder="Post Body" rows="16">Example post body...</textarea>
                    <div class="row justify-content-between">
                        <div class="col">
                            <button type="submit" class="btn btn-dark px-4">Post</button>
                        </div>

                        <div class="col-lg-6">
                            <div class="custom-file">
                                <input type="file" accept="image/*" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile" id="customFileLabel">
                                    No file selected.
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            let file = document.getElementById('customFile');

            file.addEventListener('change', function(event) {
                let fileLabel = document.getElementById('customFileLabel');
                let n = file.value.lastIndexOf('\\');
                let filename = file.value.substring(n + 1);

                fileLabel.innerHTML = filename;
            });
        </script>

    </div>
</div>

<?
draw_footer($auth);
?>