{{-- /* include('common.php');
draw_head('Pear To Pear'); */

/* $auth = $_GET['auth'];
if (!isset($_GET['auth'])) {
    $auth = "false";
}

/* $admin = $_GET['admin'];
if (!isset($_GET['admin'])) {
    $admin = "false";
}

draw_navigation($auth, "", $admin);
 */ --}}

@extends('layouts.app', ['title' => "PearToPear | Home"])
@section('content')

<!-- Page Content -->
<div class="container">

    <div class="row" style="padding: 20px 0px;">

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
                            <div id="home" class="nav-border-active">
                                Home
                            </div>
                            {{-- <a href="popularHome.php"> --}}
                            <div id="popular" class="nav-border">Popular</div>
                            {{-- </a> --}}
                            {{-- <a href="trendingHome.php"> --}}
                            <div id="trending" class="nav-border">Trending</div>
                            {{-- </a> --}}
                            {{-- <a href="universitiesHome.php"> --}}
                            <div id="universities" class="nav-border" style="border-bottom: 0px;">Universities</div>
                            {{-- </a> --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <div id="feedback-message-home">
            </div>

            @if (Auth::check())

            <!-- New Post -->
            <a href={{route('new_post')}}>
                <div class="mt-4 mt-md-1 card mb-4 mr-md-2 mr-lg-4 post-container">
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

            @endif

            <div id="posts-column-home">
                {{-- @each('partials.homePost', $posts, 'post', $user => 'user') --}}
                @foreach($posts as $post)
                @include('partials.homePost', ['post'=>$post, 'user'=>$user])
                @endforeach
            </div>

            <div class="d-flex justify-content-center col-md-11 mt-2">
                <div id="loader" class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection