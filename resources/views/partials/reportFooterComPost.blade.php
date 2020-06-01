<div class="card-footer row text-muted mx-0">
    <div class="col-md-6 align-self-center">
        <div class="card-footer-buttons row align-content-center justify-content-start">
            <a href="#"><i class="fas fa-trash-alt"></i>Delete</a>
            <a href="#"><i class="fas fa-ban"></i>Ban User</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="row align-self-center justify-content-end">
            <a href="{{ route('admin.profile', $reporter->id)}}"><img height="35" width="35"
                    src="{{ asset($reporter->photo) }}" alt="Profile Image"></a>
            <span class="px-1 align-self-center">{{ date('F d, Y', strtotime( $date ))}} by</span>
            <a class="align-self-center" href="{{ route('admin.profile', $reporter->id)}}">@<?= $reporter->username ?></a>
        </div>
    </div>
</div>