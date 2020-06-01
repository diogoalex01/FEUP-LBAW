<!-- Reported Post -->
<div class="active-tab admin-content post-tab-admin">
    <div class="post-report report card mb-4 post-container">
        <div class="card-body">
            <a href="{{ route('admin.community', $report->post->community->id)}}">
                <img height="35" class="mr-2" src="{{ asset($report->post->community->image) }}" alt="Community Image">
                /{{$report->post->community->name}} </a>
            <a href="{{ route('admin.post', $report->post->id)}}">
                <h5 class="mt-1">{{$report->post->title}}</h5>
                <p class="card-text" style="white-space: pre-line"> {{$report->post->content}} </p>
            </a>
            <p class="card-text mt-2" style="white-space: pre-line"><i
                    class="fas fa-exclamation-triangle mr-2"></i>{{ $report->report->reason }}</p>
        </div>
        @include('partials.reportFooterComPost', ['reporter' => $report->report->reporter, 'date' =>
        $report->report->time_stamp])
    </div>
</div>