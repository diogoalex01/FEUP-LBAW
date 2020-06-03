<!-- Reply -->
@if($reply->id_parent===$comment->id)
<div id="comment{{$reply->id}}" class="card mb-2 post-container post-reply">
    <div class="row pt-4">

        {{-- Votes --}}
        @if($user == null || $reply->votedUsers->where('id', "=", $user->id)->first() == null)
        @include('partials.vote', ['route'=>'/comment/'.$reply->id.'/vote', 'user'=>$user, 'object'=> $reply,
        'vote_type' => "null"])
        @else
        @include('partials.vote', ['route'=>'/comment/'.$reply->id.'/vote', 'user'=>$user, 'object'=> $reply,
        'vote_type'=> $reply->votedUsers->where('id', "=", $user->id)->first()->pivot->vote_type])
        @endif

        {{-- Content --}}
        <div class="col-md-10 mx-auto" id="comment-content-container-{{$reply->id}}">
            <p class="card-text" id="comment-body-{{$reply->id}}">
                {{$reply->content}}
            </p>
        </div>
    </div>

    <div class="card-footer row text-muted p-3"
        style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
        <div class="col-md-6 align-self-center">
            <div class="card-footer-buttons row align-content-center justify-content-start">
                @if(Auth::guard('admin')->check())
                <a href="" class="admin-delete-comment" data-object='{{$reply->id}}'><i
                        class="fas fa-trash-alt"></i>Delete</a>
                @else
                @if($user === null)
                <a href="" data-toggle="modal" data-target="#modalWelcome" class="reply-btn"><i
                        class="fas fa-reply"></i>Reply</a>
                @else
                <a href="" data-target="comment{{$reply->id}}" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                @endif

                @if($user === null || $user->id !== $reply->id_author)
                @if($user === null)
                <a href="" data-toggle="modal" data-target="#modalWelcome">
                    <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                </a>
                @else
                <a href="" data-toggle="modal" data-target="#modalCommentReport">
                    <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                </a>
                @endif
                @elseif($user !== null && $user->id === $reply->id_author)
                <a href="" class="delete-btn" data-toggle="modal" data-target="#modalDeleteComment"
                    data-object="{{$reply->id}}" data-route="/comment/{{$reply->id}}" data-type="comment">
                    <i class="fas fa-trash-alt"></i>Delete
                </a>
                <a href="" class="edit-btn" data-comment-id="{{$reply->id}}"><i class="fas fa-eraser"></i>Edit</a>
                @endif
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-self-center justify-content-end">
                @if($comment->user != null)
                @if(Auth::guard('admin')->check())
                <a href="{{route('admin.profile', $comment->user->id)}}">
                    <img class="profile-pic-small" height="35" width="35" src="{{ asset($comment->user->photo) }}"
                        alt="">
                </a>
                @else
                <a href="{{route('profile', $comment->user->id)}}">
                    <img class="profile-pic-small" height="35" width="35" src="{{ asset($comment->user->photo) }}"
                        alt="">
                </a>
                @endif
                @endif

                <span class="px-1 align-self-center">{{date('F d, Y', strtotime($reply->time_stamp))}} by </span>
                @if($reply->user == null)
                <a>@unknown</a>
                @else
                @if(Auth::guard('admin')->check())
                <a href={{ route('admin.profile', $reply->user->id) }} class="my-auto">
                    <span>@</span>{{$reply->user->username}}</a>
                @else
                <a href={{ route('profile', $reply->user->id) }} class="my-auto">
                    <span>@</span>{{$reply->user->username}}</a>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div id="replies{{$reply->id}}" style="margin-left: 6%;">
    @foreach($replies as $new_reply)
    @include('partials.reply', ['user'=>$user, 'reply'=> $new_reply, 'comment' => $reply, 'post_author' =>
    $post_author])
    @endforeach
</div>

@endif

{{-- /////////////////////////////// --}}

{{-- <div>
    <div class="row pt-4">
        <div class="d-flex align-items-end justify-content-end">
            <div class="col">
                <div class="row">
                    <div class="d-flex justify-content-between pr-1">
                        <a>
                            <i class="fas fa-chevron-up fa-lg pb-2 disabled-voting"></i>
                        </a>
                    </div>
                    <div class="d-flex justify-content-center pb-2">
                        <a>
                            <p class="mb-0"> 0 </p>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="d-flex justify-content-between pr-1">
                        <a>
                            <i class="fas fa-chevron-down fa-lg pb-2 disabled-voting"></i>
                        </a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a>
                            <p> 0 </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10 mx-auto" id="comment-content-container-${commentId}">
            <p class="card-text" id="comment-body-${commentId}" style="white-space: pre-line">
                ${commentContent}
            </p>
        </div>
    </div>

    <div class="card-footer row text-muted p-3"
        style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
        <div class="col-md-6 align-self-center">
            <div class="card-footer-buttons row align-content-center justify-content-start">
                <a href="" data-target="comment${commentId}" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                <a href="" class="delete-btn" data-toggle="modal" data-target="#modalDeleteComment"
                    data-object="${commentId}" data-route="/comment/${commentId}" data-type="comment">
                    <i class="fas fa-trash-alt"></i>Delete
                </a>
                <a href="" class="edit-btn" data-comment-id="${commentId}">
                    <i class="fas fa-eraser"></i>Edit
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-self-center justify-content-end">
                <a href="/user/${commentUser}">
                    <img class="profile-pic-small" height="35" width="35" src="${authorImage}" alt="">
                </a>
                <span class="px-1 align-self-center">Just now by</span>
                <a href="/user/${commentUser}" class="my-auto">
                    <span>@</span>${authorUsername}</a>
            </div>
        </div>
    </div>

</div>
<div id="replies${commentId}" class="test3" style="margin-left: 6%;"></div> --}}

{{-- <div class="row pt-4">
    <div class="d-flex align-items-end justify-content-end">
        <div class="col">
            <div class="row">
                <div class="d-flex justify-content-between pr-1"> <a> <i class="fas fa-chevron-up fa-lg pb-2
                            disabled-voting" aria-hidden="true"></i> </a>
                </div>
                <div class="d-flex justify-content-center pb-2"> <a>
                        <p class="mb-0"> 0 </p>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="d-flex justify-content-between pr-1"> <a> <i class="fas fa-chevron-down fa-lg pb-2
                            disabled-voting" aria-hidden="true"></i> </a>
                </div>
                <div class="d-flex justify-content-center"> <a>
                        <p> 0 </p>
                    </a> </div>
            </div>
        </div>
    </div>
    <div class="col-md-10 mx-auto" id="comment-content-container-23">
        <p class="card-text" id="comment-body-23" style="white-space: pre-line"> 6 </p>
    </div>
</div>

<div class="card-footer row text-muted p-3" style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color:
    white;">
    <div class="col-md-6 align-self-center">
        <div class="card-footer-buttons row align-content-center justify-content-start"> <a href=""
                data-target="comment23" class="reply-btn"><i class="fas fa-reply"
                    aria-hidden="true"></i>Reply</a> <a href="" class="delete-btn" data-toggle="modal"
                data-target="#modalDeleteComment" data-object="23" data-route="/comment/23" data-type="comment">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>Delete </a> <a href="" class="edit-btn
                has-listener" data-comment-id="23"> <i class="fas fa-eraser" aria-hidden="true"></i>Edit </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row align-self-center justify-content-end"> <a href="/user/23"> <img class="profile-pic-small"
                src="https://lh3.googleusercontent.com/a-/AOh14GgpaQMG6yTMv1AscMhjma0NL21tacqVpgrbQ84G" alt=""
                width="35" height="35"> </a> <span class="px-1 align-self-center">Just now by</span> <a
                href="/user/23" class="my-auto"> <span>@</span>diogo_silva56981</a> </div>
    </div>
</div>

<div id="replies23" class="test3" style="margin-left: 6%;"></div> --}}