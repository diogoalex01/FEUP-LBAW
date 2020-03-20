<?php

include('common.php');
draw_head('Settings');

$auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "false";
}

draw_navigation($auth);

?>

<!--Settings container-->
<div class="container">
    <div class="settings-form">
        <h1>Settings <i class="fa fa-sliders fa-sm" aria-hidden="true"></i>
        </h1>
        <hr>
        <div class="row">
            <!-- left column -->
            <div class="col-md-3 photo-upload-container">
                <div class="text-center">
                    <div class="text-center">
                        <img class="rounded-circle profile-pic " src="./images/avatar_male.png" alt="Profile Image">
                    </div>
                    <div class="custom-file mt-4 mb-5">
                        <input type="file" accept="image/*" class="custom-file-input" id="customFile">
                        <label class="custom-file-label text-left" for="customFile" id="customFileLabel">
                            No file selected.
                        </label>
                    </div>
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9">
                <form>
                    <div class="col ">
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">First name:</label>
                                    <input class="form-control" type="text" value="Some">
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Last name:</label>
                                    <input class="form-control" type="text" value="User">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Username:</label>
                                    <input class="form-control" type="text" value="@someuser">
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Email:</label>
                                    <input class="form-control" type="text" value="someuser@gmail.com">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Password:</label>
                                    <input class="form-control" type="password" value="11111122333">
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Confirm password:</label>
                                    <input class="form-control" type="password" value="11111122333">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Gender:</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Age:</label>
                                    <input class="form-control" type="number" value="27">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mx-sm-5 pl-1">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitches">
                                <label class="custom-control-label" for="customSwitches">Private Account</label>
                            </div>
                        </div>
                        <div class="form-group mt-4 mx-sm-5">
                            <label class=" control-label"></label>
                            <input type="button" class="btn btn-dark" value="Save Changes">
                            <span></span>
                            <input id="cancel-btn" type="reset" class="btn btn-default" value="Cancel">
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

<?php
draw_footer($auth);
?>