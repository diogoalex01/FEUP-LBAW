@extends('layouts.app', ['title' => "Post"])
@section('content')

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
                    {{-- <a href="community.php?auth="> --}}
                    <h1 class="my-4">/Porto</h1>
                    {{-- </a> --}}
                </div>
            </div>
        </div>

        <!-- Post -->
        <div class="card mb-4 post-container">
            <img class="card-img-top pl-5 pr-5 pt-5 pb-2 m-0 lg-post-image" src="{{ asset($post->image)}}"
                alt="Post Image">

            <div style="padding: 0 12%">

                <div class="row">

                    {{--  vote_content(12, 2);  --}}
                    <div class="d-flex align-items-end justify-content-end">
                        <div class="col-2">
                            <div class="d-flex justify-content-center pb-2">
                                <a>
                                    <p class="mb-0"> {{$post->upvotes}} </p>
                                </a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a>
                                    <i class="fas fa-chevron-up fa-lg pb-2"></i>
                                </a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a>
                                    <i class="fas fa-chevron-down fa-lg pb-2"></i>
                                </a>
                            </div>
                            <div class="d-flex justify-content-center">
                                <a>
                                    <p> {{$post->downvotes}} </p>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-10 mx-auto">
                        <div style="padding-top: 15px;">
                            <h2 class="card-title">{{ $post->title}}</h2>
                            <p class="card-text post-body pb-5">
                                {{$post->content}}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer row text-muted p-3"
                    style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
                    <div class="col-md-6 align-self-center ">
                        <div class="card-footer-buttons row align-content-center justify-content-start">
                            <a href="/post/{{$post->id}}#new-comment-input"><i class="fas fa-reply"></i>Reply</a>
                            @if($user->id !== $post->id_author)
                            <a data-toggle="modal" data-dismiss="modal" data-target="#modalPostReport">
                                <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                            </a>
                            @else
                            <a href="#"><i class="fas fa-trash-alt"></i>Delete</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row align-self-center justify-content-end">
                            {{-- <a href=""><img height="35" width="35" 
                                    src="./images/avatar_male.png" alt="Profile Image"></a> --}}
                            <span class="px-1 align-self-center">{{date('F d, Y', strtotime($post->time_stamp))}}
                                by</span>
                            {{-- <a class="align-self-center" href="myProfile.php?auth=&admin=">
                                @someusername</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Add Comment -->
        @if (Auth::check())
        <div class="card post-container" id="new-comment-container">
            <div class="card-body">
                <form id="new-comment-form">
                    <div class="row" style="font-size: 0.45rem;">
                        @csrf
                        <div class="col-md-10 pr-md-0">
                            <input hidden name="user_id" value={{$user->id}}>
                            <input hidden name="post_id" value={{$post->id}}>
                            <textarea id="new-comment-input" rows="1" onclick="this.rows = '8';"
                                onblur="if(this.value == '') this.rows = '1';" type="text" class="form-control mr-0"
                                placeholder="New Comment"></textarea>
                        </div>
                        <!--<div class="col-md-1 my-auto mx-auto text-right">-->
                        <div class="col-md-1 my-auto mx-auto text-right px-0 text-center comment-button">
                            <button type="submit" class="btn btn-md btn-dark" id="new-comment-btn"> Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <!-- Comment -->
        <div id="post-comment-section">
            {{-- @each('partials.comment', $comments, 'comment') --}}
            @foreach($comments as $comment)
                @include('partials.comment', ['comment'=>$comment, 'user'=>$user])
            @endforeach
        </div>
    </div>
</div>

@endsection