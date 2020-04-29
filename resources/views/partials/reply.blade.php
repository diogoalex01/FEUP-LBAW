@if($reply->id_parent===$comment->id)
<div id="comment{{$reply->id}}" class="card mb-2 post-container post-reply">
    <div class="row pt-4">

        {{-- Votes --}}
        <div class="d-flex align-items-end justify-content-end">
            <div class="col">
                <div class="row">
                    <div class="d-flex justify-content-between pr-1">
                        <a>
                            <i class="fas fa-chevron-up fa-lg pb-2"></i>
                        </a>
                    </div>
                    <div class="d-flex justify-content-center pb-2">
                        <a>
                            <p class="mb-0"> {{$reply->upvotes}} </p>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="d-flex justify-content-between pr-1">
                        <a>
                            <i class="fas fa-chevron-down fa-lg pb-2"></i>
                        </a>
                    </div>
                    <div class="d-flex justify-content-center">
                        <a>
                            <p> {{$reply->downvotes}} </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="col-md-10 mx-auto">
            <p class="card-text">
                {{$reply->content}}
            </p>
        </div>
    </div>
    <div class="card-footer row text-muted p-3"
        style="border-top: 3px solid rgba(76, 25, 27, 0.444); background-color: white;">
        <div class="col-md-6 align-self-center">
            <div class="card-footer-buttons row align-content-center justify-content-start">
                <a href="" data-target="comment{{$reply->id}}" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                @if($user != null && $reply->id_author !== $user->id)
                <a data-toggle="modal" data-dismiss="modal" data-target="#modalCommentReport">
                    <div class="a-report"><i class="fas fa-flag"></i>Report</div>
                </a>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-self-center justify-content-end">
                @if($comment->user != null)
                <a href="{{route('profile', $comment->user->id)}}">
                    <img class="profile-pic-small" height="35" width="35" src="{{ asset($comment->user->photo) }}" alt="">
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