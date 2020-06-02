<!-- User Thumbnail -->
<div id="c5" class="card mb-4 post-container">
    <div class="card-body community-thumbnail">

        @auth('admin')
        <a href="{{ route('admin.profile', $member->id) }}">
            <img class="card-img-top card-img thumbnail mr-2 mb-1 profile-pic-small" height="35"
                src="{{ asset($member->photo) }}" alt="Profile Image">
            {{ $member->username }} </a>
        @else
        <a href="{{ route('profile', $member->id) }}">
            <img class="card-img-top card-img thumbnail mr-1 mb-1 profile-pic-small" height="35"
                src="{{ asset($member->photo) }}" alt="Profile Image">
            {{ $member->username }} </a>
        @endauth

    </div>
</div>