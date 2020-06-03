<!-- Comment -->
<div class="active-tab menu-content comment-tab-menu">
    <div class="card mb-4 post-container">
        <h6 class="card-header"><i class="fa fa-terminal mr-1"></i>
            <a href="{{ route('profile', $comment->user->id )}}"> <span>@</span>{{$comment->user->username}}</a> <span
                class="text-muted">commented on</span> <a
                href="{{ route('post', $comment->post->id) }}">{{$comment->post->title}} </a></h6>
        <div class="card-body">
            <a href="{{ route('post', $comment->post->id) }}#comment{{$comment->id}}">
                <p class="card-text" style="white-space: pre-line">{{$comment->content}}</p>
            </a>
        </div>
    </div>
</div>