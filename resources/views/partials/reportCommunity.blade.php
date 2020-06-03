<!-- Reported Community -->
<div class="active-tab menu-content community-tab-menu community-{{$report->community->id}}">
    <div class="community-report report card mb-4 post-container">
        <div class="card-body">
            {{-- {{dd($report->community)}} --}}
            <a href="{{ route('admin.community', $report->community->id) }}">
                <img height="35" class="mr-2" src="{{ asset($report->community->image) }}" alt="Community Image">
                /{{ $report->community->name }} </a>
            <p class="card-text mt-2" style="white-space: pre-line"><i
                    class="fas fa-exclamation-triangle mr-2"></i>{{ $report->report->reason }}</p>
        </div>
        <div class="card-footer row text-muted mx-0">
            <div class="col-md-6 align-self-center">
                <div class="card-footer-buttons row align-content-center justify-content-start">
                    <a href="#" class="admin-delete" data-type='community' data-object='{{$report->community->id}}'><i class="fas fa-trash-alt"></i>Delete</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row align-self-center justify-content-end">
                    <a href="{{ route('admin.profile', $report->report->reporter->id) }}"><img height="35" width="35"
                            class="profile-pic-small" src="{{ asset($report->report->reporter->photo) }}"
                            alt="Profile Image"></a>
                    <span class="px-1 align-self-center">{{date('F d, Y', strtotime( $report->report->time_stamp ))}}
                        by</span>
                    <a class="align-self-center" href="{{ route('admin.profile', $report->report->reporter->id) }}">
                        <span>@<span>{{ $report->report->reporter->username }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>