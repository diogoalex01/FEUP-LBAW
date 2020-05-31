<!-- Reported Post -->
<div class="post-report report card mb-4 post-container">
    <div class="card-body">
        <a href="{{ route('admin_community', $post->community->id)}}">
            <img height="35" width="35" src="{{ asset($post->community->image) }}" alt="Community Image">
            /{{$post->community->name}} </a>
        <a href="post.php">
            <h5 class="mt-1">{{$post->title}}</h5>
            <p class="card-text">{{$post->content}} </p>
        </a>
    </div>
</div>