<!-- Reported Comment -->
<div class="comment-report report card mb-4 post-container">
    <div class="card-body">
        <a href="{{ route('admin_post', $report->post->id)}}">
            <h5> {{$report->post->title}} </h5>
        </a>
        <a href="{{ route('admin_post', $report->post->id)}}#{{$comment->id}}">
            <p class="card-text">{{ $report->reason }} </p>
        </a>
    </div>
</div>