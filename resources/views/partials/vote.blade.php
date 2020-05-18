<div class="d-flex align-items-end justify-content-end">
    <div class="col">
        <div class="row">
            <div class="d-flex justify-content-between pr-1">
                @if($user !== null)
                <a class="vote-button" data-route="{{$route}}" data-target-id="{{$object->id}}"
                    data-voter="{{$user->id}}" data-vote-type="up">
                    @else
                    <a>
                        @endif
                        <i class="fas fa-chevron-up fa-lg pb-2 vote" id="upvote-button-{{$object->id}}"></i>
                    </a>
            </div>
            <div class="d-flex justify-content-center pb-2">
                <a>
                    <p class="mb-0" id="upvote-label-{{$object->id}}"> {{$object->upvotes}} </p>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="d-flex justify-content-between pr-1">
                @if($user !== null)
                <a class="vote-button" data-route="{{$route}}" data-target-id="{{$object->id}}"
                    data-voter="{{$user->id}}" data-vote-type="down">
                    @else
                    <a>
                        @endif
                        <i class="fas fa-chevron-down fa-lg pb-2 vote" id="downvote-button-{{$object->id}}"></i>
                    </a>
            </div>
            <div class="d-flex justify-content-center">
                <a>
                    <p id="downvote-label-{{$object->id}}"> {{$object->downvotes}} </p>
                </a>
            </div>
        </div>
    </div>
</div>