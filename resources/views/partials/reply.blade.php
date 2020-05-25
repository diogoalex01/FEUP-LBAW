@if($reply->id_parent===$comment->id)
<div id="comment{{$reply->id}}" class="card mb-2 post-container post-reply">
    <div class="row pt-4">

        {{-- Votes --}}
        {{-- @include('partials.vote', ['route'=>'/comment/'.$reply->id."/vote", 'user'=>$user, 'object'=>$reply]) --}}
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
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-self-center justify-content-end">
                @if($comment->user != null)
                <a href="{{route('profile', $comment->user->id)}}">
                    <img class="profile-pic-small" height="35" width="35" src="{{ asset($comment->user->photo) }}"
                        alt="">
                </a>
                @endif

                <span class="px-1 align-self-center">{{date('F d, Y', strtotime($reply->time_stamp))}} by </span>
                @if($reply->user == null)
                <a>@unknown</a>
                @else
                <a href={{ route('profile', $reply->user->id) }} class="my-auto">
                    <span>@</span>{{$reply->user->username}}</a>
                @endif
            </div>
        </div>
    </div>
</div>
<div id="replies{{$reply->id}}">
    @foreach($replies as $new_reply)
    @include('partials.reply', ['user'=>$user, 'reply'=> $new_reply, 'comment' => $reply])
    @endforeach
</div>
@endif