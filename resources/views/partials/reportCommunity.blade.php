<!-- Reported Community -->
<!-- TODO: é preciso ids diferentes? -->
<div class="community-report report card mb-4 post-container">
    <div class="card-body">
        <a href="{{ route('admin_community', $report->reported->id) }}">
            <img height="35" width="35" src="{{ asset($report->community->image) }}" alt="Community Image">
            /{{ $report->community->name }} </a>
        <p class="card-text mt-2"> <i class="fas fa-exclamation-triangle mr-1"></i>{{ $report->reason }}</p>
    </div>
    <div class="card-footer row text-muted mx-0">
        <div class="col-md-6 align-self-center">
            <div class="card-footer-buttons row align-content-center justify-content-start">
                <a href="#"><i class="fas fa-trash-alt"></i>Delete</a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row align-self-center justify-content-end">
                <a href="{{ route('admin_user', $report->reporter->id) }}"><img height="35" width="35"
                        src="{{ asset($report->reporter->image) }}" alt="Profile Image"></a>
                <span class="px-1 align-self-center">{{ $report->date }} by</span>
                <a class="align-self-center" href="{{ route('admin_user', $report->reporter->id) }}">
                    <span>@<span>{{ $report->reporter->name }}
                </a>
            </div>
        </div>
    </div>
</div>