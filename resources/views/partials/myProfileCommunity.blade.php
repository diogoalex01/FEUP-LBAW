<!-- Community Thumbnail -->
<div id="c5" class="card mb-4 post-container">
    <div class="card-body community-thumbnail">
        <div class="community-thumbnail-row">
            <div>
                @auth('admin')
                <a href="{{ route('admin.community',$community->id) }}">
                    <img class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                        src="{{ asset($community->image) }}" alt="Community Image">
                    {{ $community->name }} </a>
                @else
                <a href="{{ route('community',$community->id) }}">
                    <img class="card-img-top card-img thumbnail mr-2 mb-1" height="35" width="35"
                        src="{{ asset($community->image) }}" alt="Community Image">
                    {{ $community->name }} </a>
                @endauth
            </div>
        </div>
    </div>
</div>