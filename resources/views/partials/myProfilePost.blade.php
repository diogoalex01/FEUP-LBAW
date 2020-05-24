<div class="card mb-4 post-container">

    <h6 class="card-header"><i class="fa fa-newspaper-o mr-1"></i>
        <a href="{{ route('profile', $user->id )}}"> <span>@</span>{{$user->username}}</a> <span class="text-muted">
            posted on <a href="{{ route('community', $post->community->id)}}">
                {{$post->community->name}}</a></span> {{-- TODO: community --}}</h6>

    <div class="mt-2 font-weight-bold">
        <a href="{{ route('post', $post->id) }}">
            @if($post->image !== null)
            <img class="card-img-top card-img thumbnail mr-1 mb-1" height="35" width="35"
                src="{{ asset($post->image) }}" alt="Post Image">
            {{$post->title}} </a>
        @else
        <span class="ml-4"> {{$post->title}} </span> </a>
        @endif
    </div>

    <div class="card-body">
        <a href="{{ route('post', $post->id )}}">
            <div class="sidebar-box">
                <p class="card-text" style="white-space: pre-line">{{$post->content}}</p>
                <p class="read-more"></p>
            </div>
        </a>
    </div>

</div>