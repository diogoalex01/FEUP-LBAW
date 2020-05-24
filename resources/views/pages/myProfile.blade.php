@extends('layouts.app', ['title' => $other_user->first_name . " ". $other_user->last_name . " | PearToPear"])
@section('content')

<!-- Page Content -->
<div class="container">

    <div class="row mt-4">

        <!-- Aside -->
        <div class="col-md-3 aside">

            <div class="profile-pic-container text-center">
                <img class="profile-pic" src={{ asset($other_user->photo) }} alt="Profile Image">
            </div>

            {{-- <div class="row">
                 if ($admin === "true") { 
                <div class="text-center mx-auto">
                    <input type="button" class="btn btn-outline-danger" value="Ban User">
                </div>

            </div> --}}

            <div class="card my-4 aside-container">
                <h5 class="card-header aside-container-top" style="background-color: white;">
                    Profile
                </h5>
                <div class="card-body">
                    <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                        <div class="col-1 pl-0">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="col">
                            {{ $other_user->credibility }} points
                        </div>
                    </div>
                    <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                        <div class="col-1 pl-0">
                            <i class="fa fa-newspaper-o"></i>
                        </div>
                        <div class="col">
                            {{$nPosts}} posts {{-- nr de posts --}}
                        </div>
                    </div>

                    <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                        <div class="col-1 pl-0">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <div class="col">
                            {{ $age }} y.o.
                        </div>
                    </div>
                    <div class="row mb-2 ml-1 d-flex justify-content-start align-items-center">
                        <div class="col-1 pl-0">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                        <div class="col">
                            {{ $other_user->gender }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Categories -->
            <div class="card aside-container">              
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-start">
                            <div id="activity_menu" class="profile-aside nav-border-active">
                                <i class="fas fa-chart-line mr-2"></i>
                                Activity</div>
                            <div id="community_menu" class="profile-aside nav-border" style="border-bottom: 0px;">
                                <i class="fas fa-users mr-2"></i>
                                Communities</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <h1 class="my-4 username-header"> <span>@</span>{{$other_user->username}}</h1>

            <!-- New Post -->
            <a href="/new_post">
                <div class="card mb-4 post-container">
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

            <!-- Activity -->
            <div class="active-tab profile-content">
                @foreach($posts as $post)
                @include('partials.myProfilePost', ['post' => $post, 'user' => $other_user ])
                @endforeach
            </div>

            <!-- Communities -->
            <div class="hidden-tab profile-content" style="display: none;">
                @foreach($communities as $community)
                @include('partials.myProfileCommunity', ['community' => $community, 'user' => $other_user ])
                @endforeach
            </div>

        </div>
    </div>
</div>

@endsection