<!-- Reported Comment -->
<div class="active-tab menu-content comment-tab-menu author-comment-{{$report->comment->id_author}} comment-{{$report->comment->id}}">
    <div class="comment-report report card mb-4 post-container">
        <div class="card-body">
            <a href="{{ route('admin.post', $report->comment->post->id)}}">
                <h5> {{$report->comment->post->title}} </h5>
            </a>
            <a href="{{ route('admin.post', $report->comment->post->id)}}#comment{{$report->comment->id}}">
                <p class="card-text" style="white-space: pre-line"> {{$report->comment->content}} </p>
            </a>
            <p class="card-text mt-2" style="white-space: pre-line"><i
                    class="fas fa-exclamation-triangle mr-2"></i>{{ $report->report->reason }}</p>
        </div>
        @include('partials.reportFooterComPost', ['reporter' => $report->report->reporter, 'date' =>
        $report->report->time_stamp, 'id' => $report->comment->id, 'type' => 'comment', 'author_id'=> $report->comment->id_author])
    </div>
</div>