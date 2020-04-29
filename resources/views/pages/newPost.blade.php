@extends('layouts.app', ['title' => "New Post"])
@section('content')

<!-- Page Content -->
<div class="container">

    <!-- Posts Column -->
    <div class="col-md-14">
        <h1 class="my-4 text-center">New Post <i class="fa fa-keyboard-o" aria-hidden="true"></i></h1>
        <hr>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="my-auto">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- New Post Input -->
        <div class="card mb-4 post-container new-post">
            <div class="card-body ">
                <form id="new-post-form" class="needs-validation" novalidate action="{{route('new_post')}}"
                    method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="dropdown-container">
                        <div class="validate-me">
                            <input name="community" autocomplete="off" type="text" class="form-control"
                                placeholder="ExampleCommunity" required>
                            <div class="invalid-feedback">
                                This field is required!
                            </div>
                        </div>

                        <div class="dropdown">

                            {{-- <a id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="">
                                <i class="fas fa-bell bell fa-lg"></i>
                            </a> --}}

                            <div class="dropdown-menu search dropdown-menu search-wrapper" role="menu"
                                aria-labelledby="dLabel" style="background-color: #f8f9fa;">
                            </div>
                        </div>
                    </div>

                    <div class="row privacy-toggle">
                        <p class="m-3 font-weight-bold">Set Privacy</p>
                        <div class="form-check form-check-inline">
                            <div class="custom-control custom-switch">
                                <input name="private" type="checkbox" class="custom-control-input"
                                    id="communityPrivacyToggle">
                                <label class="custom-control-label" for="communityPrivacyToggle">Private
                                    community</label>
                            </div>
                        </div>
                    </div>

                    <div class="validate-me">
                        <input name="title" type="text" class="form-control mb-2" placeholder="Example Title" required>
                        <div class="invalid-feedback mb-3">
                            This field is required!
                        </div>
                    </div>

                    <div class="validate-me">
                        <textarea name="post_content" type="text" class="form-control"
                            placeholder="Example post body..." rows="16" required></textarea>
                        <div class="invalid-feedback">
                            This field is required!
                        </div>
                    </div>

                    <div class="row justify-content-between">
                        <div class="col mt-3">
                            <button type="submit" class="btn btn-dark px-4">Post</button>
                        </div>

                        <div class="col-lg-6">
                            <div class="custom-file mt-3">
                                <input name="image" type="file" accept="image/*" class="custom-file-input"
                                    id="customFile">
                                <label class="custom-file-label" for="customFile" id="customFileLabel">
                                    No file selected.
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection