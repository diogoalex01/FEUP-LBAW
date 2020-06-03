@extends( (Auth::guard('admin')->check()) ? 'layouts.admin' : 'layouts.app', [ 'title' => $community->name ." |
Community" ])
@section('content')

<!-- Page Content -->
<div class="container community-page-container" data-object-id="{{$community->id}}">
    <div class="row" style="padding: 20px 0px;">

        <!-- Aside -->
        <div class="col-md-3 aside mb-4">
            @include('partials.categoriesCommunity')
        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <div class="row my-auto">
                <div class="col-md-2 text-center community-pic-container-comm">
                    <img class="community-pic" src="{{ asset($community->image)}}" alt="Community Image">
                </div>

                <div class="col-md-7 my-auto">
                    <h1 class="my-0">{{$community->name}}</h1>
                </div>

                @if(Auth::guard('admin')->check())
                <form class="text-center d-flex align-items-center justify-content-center"
                    onsubmit="deleteCommunity(event,{{$community->id}});">
                    <div class="col-md-1 d-flex align-items-center justify-self-right">
                        <input type="submit" class="btn btn-outline-danger" value="Delete" id="delete-button">
                    </div>
                </form>

                @elseif(!$owner && !Auth::guest())

                @if($community->private)
                <form class=" text-center d-flex align-items-center"
                    onsubmit="joinPrivateCommunity(event,{{$community->id}})">
                    @else
                    <form class=" text-center d-flex align-items-center"
                        onsubmit="joinCommunity(event,{{$community->id}})">
                        @endif
                        <div class="text-center d-flex align-items-left">

                            @if (!$isMember)
                            @if($request_status === "pending")
                            <input type="submit" class="btn btn-dark" value="Pending" id="join-button"
                                style="width: 80px;">
                            @else
                            <input type="submit" class="btn btn-dark" value="Join" id="join-button"
                                style="width: 80px;">
                            @endif
                            @else
                            <input type="submit" class="btn btn-dark" value="Leave" id="join-button"
                                style="width: 80px;">
                            @endif
                        </div>
                    </form>
                    @endif

                    @if(!Auth::guard('admin')->check() && $owner)
                    <div class="text-center d-flex align-items-center">
                        <div class="text-center d-flex align-items-center">
                            <input class="report-button btn btn-outline-danger ml-3" type="submit" data-toggle="modal"
                                data-object="{{$community->id}}" data-dismiss="modal"
                                data-target="#modalCommunityDeletion" value="Report" style="width: 80px;">
                        </div>
                    </div>
                    @elseif(!Auth::guard('admin')->check() && !Auth::guest() && (!$community->private ||
                    ($community->private && $isMember)))
                    <div class="text-center d-flex align-items-center">
                        <div class="text-center d-flex align-items-center">
                            <input class="report-button btn btn-outline-danger ml-3" type="submit" data-toggle="modal"
                                data-object="{{$community->id}}" data-dismiss="modal"
                                data-target="#modalCommunityReport" value="Report" style="width: 80px;">
                        </div>
                    </div>
                    @endif
            </div>

            @if (!Auth::guard('admin')->check() && Auth::check() && ($isMember || $owner))
            <!-- New Post -->
            <a href="{{ route('new_post')}}">
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

            @if(Auth::guard('admin')->check() || $owner || !$community->private || ($community->private && Auth::check()
            && $isMember))
            <div id="posts-column-community">
                @foreach($posts as $post)
                @include('partials.homePost', ['post'=>$post])
                @endforeach
            </div>

            <div class="d-flex justify-content-center col-md-11 mt-2">
                <div id="loader" class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            @endif

            <div id="no-content" class="card mb-4 mr-md-2 mr-lg-4 post-container" style="display: none;">

                <h5 class="card-header aside-container-top d-flex align-items-center">

                    <div class="col-1">
                        <i class="far fa-laugh-beam fa-2x"></i>
                    </div>
                    <div class="col">
                        Welcome!
                    </div>

                </h5>

                <div class="card-body justify-content-start">
                    <p class="card-text">This community has no content! <br> Start by writing a new post! Happy writing!
                    </p>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection