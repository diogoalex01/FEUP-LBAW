@extends( (Auth::guard('admin')->check()) ? 'layouts.admin' : 'layouts.app', [ 'title' => $other_user->first_name . " ".
$other_user->last_name . " | PearToPear" ])

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
            @auth('admin')
            <div class="row mt-3 d-flex justify-content-center">
                <form class="text-center d-flex align-items-center justify-content-center"
                    onsubmit="blockUser(event,{{$other_user->id}});">
                    <div class="">
                        <input type="submit" class="btn btn-outline-danger" value="Delete" id="delete-button">
                    </div>
                </form>
            </div>
            @elseif(Auth::check())
            @if($other_user->id !== $user->id && !$isBlocked)
            {{-- check if they are friends --}}
            <div class="row mt-3">
                @if($isBlocking)
                <form class="col-6 text-center d-flex align-items-center justify-content-end"
                    onsubmit="blockUser(event,{{$other_user->id}});">
                    <div class="">
                        <input type="submit" class="btn btn-outline-danger" value="Unblock" id="block-button">
                    </div>
                </form>
                @else
                <form class="col-6 text-center d-flex align-items-center justify-content-end"
                    onsubmit="blockUser(event,{{$other_user->id}});">
                    <div class="">
                        <input type="submit" class="btn btn-outline-danger" value="Block" id="block-button">
                    </div>
                </form>
                @if($follows)
                <form class="col-1 text-center d-flex align-items-center"
                    onsubmit="followUser(event,{{$other_user->id}});">
                    <div class="">
                        <input type="submit" class="btn btn-dark" value="Unfollow" id="follow-button">
                    </div>
                </form>
                @elseif($follow_status == "pending")
                <form class="col-1 text-center d-flex align-items-center"
                    onsubmit="followUser(event,{{$other_user->id}});">
                    <div class="">
                        <input type="submit" class="btn btn-dark" value="Pending" id="follow-button">
                    </div>
                </form>
                @else
                <form class="col-1 text-center d-flex align-items-center"
                    onsubmit="followUser(event,{{$other_user->id}})">
                    <div class="">
                        <input type="submit" class="btn btn-dark" value="Follow" id="follow-button">
                    </div>
                </form>
                @endif
                @endif
            </div>
            @endif
            @endauth

            <div class="card my-4 aside-container">
                <h5 class="card-header aside-container-top" style="background-color: white;">
                    <span>{{$other_user->first_name}}</span>
                    <span>{{$other_user->last_name}}</span>
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
                            {{$nPosts}} posts
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

            @if((!$other_user->private || !Auth::check() || $other_user->id === $user->id || $follows) && (!$isBlocked && !$isBlocking))
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
            @endif

        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <h1 class="my-4 username-header"> <span>@</span>{{$other_user->username}}</h1>

            <div class="a-report col-md-12">
                @if(Auth::guest())
                <a data-toggle="modal" data-dismiss="modal" data-target="#modalWelcome">
                    <i class="fas fa-flag"></i>Report
                </a>
                @else
                <a class ="report-button" data-toggle="modal" data-dismiss="modal" data-object ="{{$user->id}}" data-target="#modalUserReport">
                    <i class="fas fa-flag"></i>Report
                </a>
                @endif
            </div>
            <!-- New Post -->
            @if(Auth::check() && ($other_user->id === $user->id))
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
            @endif

            @if((!$other_user->private || !Auth::check() || $other_user->id === $user->id || $follows) && (!$isBlocked && !$isBlocking))
            <!-- Activity -->
            <div class="active-tab profile-content">
                @foreach($activities as $activity)
                @if($activity instanceof App\Post)
                @include('partials.myProfilePost', ['post' => $activity, 'user' => $other_user ])
                @else
                @include('partials.myProfileComment', ['comment' => $activity, 'user' => $other_user ])
                @endif
                @endforeach
            </div>

            <!-- Communities -->
            <div class="hidden-tab profile-content" style="display: none;">
                @foreach($communities as $community)
                @include('partials.myProfileCommunity', ['community' => $community, 'user' => $other_user ])
                @endforeach
            </div>
            @else

            <!-- Private notice -->
            <div class="card mb-4 post-container">

                <h5 class="card-header aside-container-top d-flex align-items-center">

                    <div class="col-1 pr-lg-0">
                        <i class="fas fa-user-lock"></i>
                    </div>
                    <div class="col pl-lg-0">
                        You can't check this account
                    </div>

                </h5>

                <div class="card-body justify-content-start">
                    <p class="card-text">You may have blocked this person, have been blocked or this is a private
                        account.</p>
                </div>

            </div>
            @endif

        </div>
    </div>
</div>

@endsection