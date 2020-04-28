
<div class="card mb-4 post-container">

    <h6 class="card-header"><i class="fa fa-newspaper-o mr-1"></i>
        <a href="{{ route('profile', $user->id )}}"> @ {{$user->username}}</a> <span
            class="text-muted"> posted on COMMUNITY</span> {{-- meter a comunidade --}}
            {{-- href="community.php?auth=&admin=">/</a> --}}</h6>

        <div class="my-2 font-weight-bold">
        <a href="{{ route('post', $post->id )}}"> 
            @if($post->image !== null)
            <img class="card-img-top thumbnail mr-1 mb-1"
            height="35" width="35" src="{{ asset($post->image)}}" alt="Post Image">
            {{$post->title}} </a>
            @else
            <span class = "ml-4 "> {{$post->title}} </span> </a>
            @endif
        </div>

    <div class="card-body post-thbn-body">

        <a href="{{ route('post', $post->id )}}">
            <p class="card-text">{{$post->content}}</p>
        </a>
    </div>

</div>