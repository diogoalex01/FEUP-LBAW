<div class="card mb-4 post-container">
    <h6 class="card-header"><i class="fa fa-newspaper-o mr-1"></i>
        @auth('admin')
        <a href="{{ route('admin.profile', $post->user->id )}}"> <span>@</span>{{$post->user->username}}</a> <span
            class="text-muted">
            posted on <a href="{{ route('admin.community', $post->community->id)}}">
                {{$post->community->name}}</a></span></h6>
    @else
    <a href="{{ route('profile', $post->user->id )}}"> <span>@</span>{{$post->user->username}}</a> <span class="text-muted">
        posted on <a href="{{ route('community', $post->community->id)}}">
            {{$post->community->name}}</a></span></h6>
    @endauth

    <div class="mt-2 font-weight-bold">
        @auth('admin')
        <a href="{{ route('admin.post', $post->id) }}">
            @if($post->image !== null)
            <img class="card-img-top card-img thumbnail mr-1 mb-1" height="35" width="35"
                src="{{ asset($post->image) }}" alt="Post Image">
            {{$post->title}} </a>
        @else
        <span class="ml-4"> {{$post->title}} </span> </a>
        @endif
        @else
        <a href="{{ route('post', $post->id) }}">
            @if($post->image !== null)
            <img class="card-img-top card-img thumbnail mr-1 mb-1" height="35"
                src="{{ asset($post->image) }}" alt="Post Image">
            {{$post->title}} </a>
        @else
        <span class="ml-4"> {{$post->title}} </span> </a>
        @endif
        @endauth
    </div>

    <div class="card-body">
        @auth('admin')
        <a href="{{ route('admin.post', $post->id )}}">
            <div class="sidebar-box">
                <p class="card-text" style="white-space: pre-line">{{$post->content}}</p>
                <p class="read-more"></p>
            </div>
        </a>
        @else
        <a href="{{ route('post', $post->id )}}">
            <div class="sidebar-box">
                <p class="card-text" style="white-space: pre-line">{{$post->content}}</p>
                <p class="read-more"></p>
            </div>
        </a>
        @endauth
    </div>

</div>