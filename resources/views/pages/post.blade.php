@extends('layouts.app', ['title' => $post->title])
@section('content')

<!-- Page Content -->
<div class="container">

    <!-- Posts Column -->
    <div class="col-md-12">

        <div class="container">
            <div class="row">
                <div class="col-md-2 text-center community-pic-container">
                    <a href="{{ route('community', $post->community->id)}}">
                        <img class="community-pic" src="{{ asset($post->community->image) }}" alt="Community Image">
                    </a>
                </div>
                <div class="col-md-7 my-auto">
                    <h1 class="my-4"> <a href="{{ route('community', $post->community->id)}}">
                            {{$post->community->name}} </a></h1>
                </div>
            </div>
        </div>

        <!-- Post -->
        <div class="card mb-4 post-container">
            @if($post->image != null)
            <img class="card-img-top card-img pl-5 pr-5 pt-5 pb-2 m-0 lg-post-image" src="{{ asset($post->image)}}"
                alt="Post Image">
            @endif

            <div style="padding: 0 12%">

                <div class="row">

                    {{-- Votes --}}
                    @if($user == null || $post->votedUsers->where('id', "=", $user->id)->first() == null)
                    @include('partials.vote', ['route'=>'/post/'.$post->id.'/vote', 'user'=>$user, 'object'=> $post,
                    'vote_type' => "null"])
                    @else
                    @include('partials.vote', ['route'=>'/post/'.$post->id.'/vote', 'user'=>$user, 'object'=> $post,
                    'vote_type'=> $post->votedUsers->where('id', "=", $user->id)->first()->pivot->vote_type])
                    @endif

                <div class="col-md-10 mx-auto" id="post-content-container-{{$post->id}}">
                        <div style="padding-top: 15px;">
                            <h2 class="card-title">{{ $post->title}}</h2>
                            <p class="card-text pb-5" id="post-body-{{$post->id}}" style="white-space: pre-line">
                                {{$post->content}}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer row text-muted p-3"
                    style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
                    <div class="col-md-6 align-self-center ">
                        <div class="card-footer-buttons row align-content-center justify-content-start">
                            @if($user != null)
                            <a href="/post/{{$post->id}}#new-comment-input"><i class="fas fa-reply"></i>Reply</a>
                            @else
                            <a href="" data-toggle="modal" data-target="#modalWelcome"><i
                                    class="fas fa-reply"></i>Reply</a>
                            @endif

                            @if($user === null || $user->id !== $post->id_author)
                            @if($user === null)
                            <a href="" data-toggle="modal" data-target="#modalWelcome">
                                <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                            </a>
                            @else
                            <a href="" data-toggle="modal" data-target="#modalPostReport">
                                <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                            </a>
                            @endif
                            @elseif($user !== null && $user->id === $post->id_author)
                            <a href="" class="delete-btn" data-toggle="modal" data-target="#modalDeletePost"
                                data-object="{{$post->id}}" data-route="/post/{{$post->id}}" data-type="post">
                                <i class="fas fa-trash-alt"></i>Delete
                            </a>
                            <a href="" id="edit-post-btn" data-post-id="{{$post->id}}"><i class="fas fa-eraser"></i>Edit
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row align-self-center justify-content-end">
                            @if($post->user != null)
                            <a href="{{route('profile', $post->user->id)}}">
                                <img class="profile-pic-small" height="35" width="35"
                                    src="{{ asset($post->user->photo) }}" alt="">
                            </a>
                            @endif

                            <span class="px-1 pl-2 align-self-center">{{date('F d, Y', strtotime($post->time_stamp))}}
                                by</span>
                            @if($post->user == null)
                            <a class="align-self-center">
                                @unknown </a>
                            @else
                            <a class="align-self-center" href={{ route('profile', $post->user->id)}}>
                                <span>@</span>{{$post->user->username}}</a>
                            {{-- <a class="align-self-center" href="myProfile.php?auth=&admin=">
                                @someusername</a> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

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
            @include('partials.comment', ['comment'=>$comment, 'user'=>$user, 'replies'=> $replies])
            @endforeach
        </div>
    </div>
</div>

<div class="modal" id="modalDeletePost" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body login-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <section>
                    <div id="deleteAccountForm" class="container mb-3">
                        <h2 class="text-dark title-padding title-mobile">Delete Post
                            {{-- <i class="fas fa-exclamation-circle pl-2 text-danger"></i> --}}
                        </h2>
                        <hr>
                        <label class="col control-label pl-0 mx-0">This action cannot be undone. This will permanently
                            <b>delete</b> your post.</label>

                        <div class="row justify-content-end my-2 mx-1">
                            <button class="btn btn-secondary my-2" data-toggle="modal" data-dismiss="modal"
                                data-target="#">Take me back</button>
                            <button id="confirm-delete-post" class="btn my-2 ml-1 text-danger delete-confirm"
                                data-toggle="modal" data-dismiss="modal">I'm sure</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalDeleteComment" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body login-modal">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <section>
                    <div id="deleteAccountForm" class="container mb-3">
                        <h2 class="text-dark title-padding title-mobile">Delete Comment
                        </h2>
                        <hr>
                        <label class="col control-label pl-0 mx-0">This action cannot be undone. This will permanently
                            <b>delete</b> your comment.</label>

                        <div class="row justify-content-end my-2 mx-1">
                            <button class="btn btn-secondary my-2" data-toggle="modal" data-dismiss="modal"
                                data-target="#">Take me back</button>
                            <button id="confirm-delete-comment" class="btn my-2 ml-1 text-danger delete-confirm"
                                data-toggle="modal" data-dismiss="modal">I'm sure</button>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

@endsection