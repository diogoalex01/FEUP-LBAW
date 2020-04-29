<!-- Post -->
<div class="row no-gutters">
    <div class="col-md-11 pr-0 mr-0">
        <div class="card mb-4 post-container">
            <a href="/post/{{ $post->id }}">
                {{--<a href="post.php?auth=&admin=--}}
                @if($post->image != null)
                <img class="card-img-top post-image "style="padding: 15px 15px 0px;" src={{ asset($post->image)}}
                    alt="Post Image">
                @endif
            </a>
            <div style="padding: 0 12%">
                <div class="row">

                    {{--p //vote_content($up_votes, $down_votes);  --}}

                    <div class="col-md-10 mx-auto">
                        <a href="/post/{{ $post->id }}">
                            <div style="padding: 15px 0;">
                                <h2 class="card-title">{{ $post->title }}</h2>
                                <p class="card-text">{{ $post->content }}
                                </p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="card-footer row text-muted py-3 px-3"
                    style="border-top: 1px solid rgb(76, 25, 27); background-color: white;">
                    <div class="col-md-5 align-self-center justify-content-start">
                        <div class="card-footer-buttons row align-content-center justify-content-start">
                            <!-- <a href="post.php?auth=&admin=<>#new-comment-input"><i class="fas fa-reply"></i>Reply</a>-->
                            <div class="a-report">
                                <a data-toggle="modal" data-dismiss="modal" data-target="#modalPostReport">
                                    <i class="fas fa-flag"></i>Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row align-self-center justify-content-end">

                            {{-- @if (strlen($post->user->password) < 32)
                            <a href="user/{{$post->id_author}}"> <img 
                                class="rounded-circle"
                                height="35" width="35" src="{{ asset($post->user->photo) }}"
                                alt="Profile Image">
                            </a>
                            @else
                                <a href="user/{{$post->id_author}}"> <img 
                                    class="rounded-circle"
                                    height="35" width="35" src="{{ url($post->id_author->photo) }}"
                                    alt="Profile Image">
                                </a>
                            @endif --}}

                            <span class="px-1 align-self-center">{{date('F d, Y', strtotime($post->time_stamp))}}
                                by</span>
                            {{-- <a class="align-self-center" href="{{$post->id_author->id}}">@ {{$post->id_author->username}} </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-1 px-0 ml-0">
        <div class="row community-row">
            <div>
                <a href="community.php">
                    {{-- <h5 class="community-tag mb-0">{{post->community}}</h5>--}}
                </a>
            </div>
        </div>
    </div>
</div>