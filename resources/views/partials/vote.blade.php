<div class="d-flex align-items-end justify-content-end">
    <div class="col">
        <div class="row">
            <div class="d-flex justify-content-between pr-1">
                @if($user !== null)
                @if($user->id === $object->id_author)
                <a class="vote-button disabled-voting">
                    <i class="fas fa-chevron-up fa-lg pb-2" id="upvote-button-{{$object->id}}"
                        {{ strcmp($vote_type, "up" )===0 ? "data-checked = data-checked" : "" }}></i>
                </a>
                @else
                <a class="vote-button" data-route="{{$route}}" data-target-id="{{$object->id}}"
                    data-voter="{{$user->id}}" data-vote-type="up">
                    <i class="fas fa-chevron-up fa-lg pb-2" id="upvote-button-{{$object->id}}"
                        {{ strcmp($vote_type, "up" )===0 ? "data-checked = data-checked" : "" }}></i>
                </a>
                @endif
                @else
                <a data-toggle="modal" data-target="#modalWelcome">
                    <i class="fas fa-chevron-up fa-lg pb-2" id="upvote-button-{{$object->id}}"
                        {{ strcmp($vote_type, "up" )===0 ? "data-checked = data-checked" : "" }}></i>
                </a>
                @endif

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
                @if($user->id === $object->id_author)
                <a class="vote-button disabled-voting">
                    <i class="fas fa-chevron-down fa-lg pb-2" id="downvote-button-{{$object->id}}"
                        {{ strcmp($vote_type, "down" )===0 ? "data-checked = data-checked" : "" }}></i>
                </a>

                @else
                <a class="vote-button" data-route="{{$route}}" data-target-id="{{$object->id}}"
                    data-voter="{{$user->id}}" data-vote-type="down">
                    <i class="fas fa-chevron-down fa-lg pb-2" id="downvote-button-{{$object->id}}"
                        {{ strcmp($vote_type, "down" )===0 ? "data-checked = data-checked" : "" }}></i>
                </a>
                @endif
                @else
                <a data-toggle="modal" data-target="#modalWelcome">
                    <i class="fas fa-chevron-down fa-lg pb-2" id="downvote-button-{{$object->id}}"
                        {{ strcmp($vote_type, "down" )===0 ? "data-checked = data-checked" : "" }}></i>
                </a>
                @endif
            </div>
            <div class="d-flex justify-content-center">
                <a>
                    <p id="downvote-label-{{$object->id}}"> {{$object->downvotes}} </p>
                </a>
            </div>
        </div>
    </div>
</div>