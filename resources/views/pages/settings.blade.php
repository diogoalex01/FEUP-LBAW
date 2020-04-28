@extends('layouts.app', ['title' => "Settings"])
@section('content')

<!--Settings container-->
<div class="container">
    <div class="settings-form mt-3">
        <h1>Settings <i class="fa fa-sliders fa-sm" aria-hidden="true"></i> </h1>
        <hr>

        <div class="row">

            <!-- left column -->
            <div class="col-md-3 photo-upload-container">
                <div class="text-center">
                    <div class="text-center">
                        {{--{{ auth()->user->photo != null? auth()->user->photo : asset('img/avatar_male.png') }} --}}
                        <img class="rounded-circle profile-pic" src="{{ $user->photo }}"
                            alt="Profile Image">
                    </div>
                    <div class="custom-file mt-4 mb-5">
                         <input name="image" form="edit-user" type="file" accept="image/*" class="custom-file-input"
                            id="customFile"> 
                        <label class="custom-file-label text-left" for="customFile" id="customFileLabel">
                            No file selected.
                        </label>
                    </div>
                </div>
            </div>

            <!-- edit form column -->
            <div class="col-md-9">
                <div id="feedback-message">
                </div>

                <!-- enctype="multipart/form-data"-->
                <form id="edit-user" class="settings" onsubmit="return mySubmitFunction()">
                    @csrf
                    <div class="col">
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class="control-label">First name:</label>
                                    <input class="form-control" type="text" name="first_name" id="first_name"
                                        value="{{$user->first_name}}">
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Last name:</label>
                                    <input class="form-control" type="text" name="last_name" id="last_name"
                                        value="{{$user->last_name}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Username:</label>
                                    <input class="form-control" type="text" name="username" id="username"
                                        value="{{$user->username}}">
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Email:</label>
                                    <input class="form-control" type="text" name="email" id="email"
                                        value="{{$user->email}}">
                                </div>
                            </div>

                        </div>

                        @if (strlen($user->password) > 32)
                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Password:</label>
                                    <input class="form-control" type="password" name="password" id="password"
                                        placeholder="Password" required>
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Confirm password:</label>
                                    <input class="form-control" type="password" name="password_confirmation"
                                        id="password_confirmation" placeholder="Confirm your password" required>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-4 mx-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Gender:</label>
                                    <select class="form-control" id="gender" name="gender">
                                        @if ($user->gender == "male") {
                                        <option value="male" selected="selected">Male</option>
                                        }
                                        @else {
                                        <option value="male">Male</option>
                                        }
                                        @endif

                                        @if ($user->gender == "female") {
                                        <option value="female" selected="selected">Female</option>
                                        }
                                        @else {
                                        <option value="female">Female</option>
                                        }
                                        @endif

                                        @if ($user->gender == "other") {
                                        <option value="other" selected="selected">Other</option>
                                        }
                                        @else {
                                        <option value="other">Other</option>
                                        }
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 ml-sm-5">
                                <div class="form-group">
                                    <label class=" control-label">Birthdate:</label>
                                    <input class="form-control" type="date" name="birthday" id="birthday"
                                        value="{{ $user->birthday }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mx-sm-5 pl-1">
                            <div class="custom-control custom-switch">
                                @if($user->private)
                                <input type="checkbox" class="custom-control-input" checked id="privacyToggle">
                                @else
                                <input type="checkbox" class="custom-control-input" id="privacyToggle">
                                @endif

                                <label class="custom-control-label" for="privacyToggle"></label>

                            </div>
                        </div>
                        <div class="form-group mt-4 mx-sm-5">
                            <label class="control-label"></label>
                            <input type="submit" class="btn btn-dark" value="Save Changes">
                            <input id="cancel-btn" type="reset" class="btn btn-default" value="Cancel">
                        </div>
                    </div>
                </form>

                <div class="container mx-md-5 mt-5 p-3 delete-container" id="delete-container"
                    style="border: 2px solid #4c191b">

                    <form id="delete-user" class="my-auto" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-9 my-auto">
                                <p class="my-0">Do you want to delete your account?</p>
                            </div>
                            <div class="col-md-3 delete-column">
                                <div>
                                    <button type="button" id="delete-red-button" data-toggle="modal" data-target="#modalDelete" class="btn btn-outline-danger my-auto">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalCommunityTitle" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body login-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <section>
                    <div id="deleteAccountForm" class="container mb-3">
                        <h2 class="text-dark title-padding title-mobile">Delete Account
                        </h2>
                        <hr>
                        <label class="col control-label pl-0 mx-0">This action cannot be undone. This will permanently <b>delete</b> your account. Be prePEARed!</label>
                        <label class="col control-label pl-0 mx-0" for="#delete-confirm-username">Enter your <b class="text-danger">username</b> to confirm:</label>
                        <input class="form-control" type="text" name="delete-confirm-username" id="delete-confirm-username">
                        <input hidden type="text" value="{{$user->username}}" id="delete-user-solution">
                        <div class="form-group mx-sm-0 mt-3 pl-1">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="deleteToggle">
                                <label class="custom-control-label" for="deleteToggle">
                                    Keep my content
                            </div>
                        </div>
                        <div class="alert alert-danger" id="delete-warning-box" hidden = "hidden">
                            <h4>Warning!<h4>
                            <h6>By checking this box you agree to the deletion of all your content!</h6>
                        </div>
                             <div class="row justify-content-end my-2 mx-1">
                            <button class="btn btn-secondary my-2" data-toggle="modal" data-dismiss="modal"
                                data-target="#">Take me back</button>
                            <button id="deleteAccount" class="btn my-2 ml-1 text-danger" data-toggle="modal" data-dismiss="modal"
                                data-target="#" disabled>I'm sure</button>
                            </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

@endsection