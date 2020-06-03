<div class="card-footer row text-muted mx-0">
    <div class="col-md-6 align-self-center">
        <div class="card-footer-buttons row align-content-center justify-content-start">
            <a href="" class="admin-delete" data-type="{{$type}}" data-object="{{$id}}"><i
                    class="fas fa-trash-alt"></i>Delete</a>
            <a href="" class="admin-delete" data-type="user-{{$type}}" data-object="{{$author_id}}"
                data-target="{{$id}}"><i class="fas fa-ban"></i>Ban User</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row align-self-center justify-content-end">
            @if($report->report->reporter != null)
            <a href="{{ route('admin.profile', $reporter->id)}}"><img height="35" width="35" class="profile-pic-small"
                    src="{{ asset($reporter->photo) }}" alt="Profile Image"></a>
            @endif
            <span class="px-1 align-self-center">{{ date('F d, Y', strtotime( $date ))}} by</span>
            @if($reporter == null)
            <a>@unknown</a>
            @else
            <a href="" class="align-self-center"
                href="{{ route('admin.profile', $reporter->id)}}">@<?= $reporter->username ?></a>
            @endif
        </div>
    </div>
</div>