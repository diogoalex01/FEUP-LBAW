<!-- Post -->
<div class="row no-gutters">
    <div class="col-md-11 pr-0 mr-0">
        <div class="card mb-4 post-container">
            <a href="/post/{{ $post->id }}">
                {{--<a href="post.php?auth=&admin=--}}
                @if($post->image != null)
                <img class="card-img-top card-img post-image" style="padding: 15px 15px 0px;"
                    src={{ asset($post->image)}} alt="Post Image">
                @endif
            </a>
            <div style="padding: 0 12%">
                <div class="row">

                    {{-- Votes --}}
                    @if($user == null || $post->votedUsers->where('id', "=", $user->id)->first() == null)
                    @include('partials.vote', ['route'=>'/post/'.$post->id.'/vote', 'user'=>$user, 'object'=> $post,
                    'vote_type' => "null"])
                    @else
                    @include('partials.vote', ['route'=>'/post/'.$post->id.'/vote', 'user'=>$user, 'object'=> $post,
                    'vote_type'=> $post->votedUsers->where('id', "=", $user->id)->first()->pivot->vote_type])
                    @endif

                    <div class="col-md-10 mx-auto">
                        <a href="/post/{{ $post->id }}">
                            <div style="padding: 15px 0;">
                                <h2 class="card-title">{{ $post->title }}</h2>
                                <div class="post-box">
                                    <p class="card-text" style="white-space: pre-line">{{ $post->content }} </p>
                                    <p class="read-more"></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="card-footer row text-muted py-3 px-3"
                    style="border-top: 1px solid rgb(76, 25, 27); background-color: white;">
                    <div class="col-md-5 align-self-center justify-content-start">
                        <div class="card-footer-buttons row align-content-center justify-content-start">
                            @if(Auth::guest())
                            <a href="{{route('post',$post->id)}}"><i class="fas fa-reply"></i>Reply</a>
                            @else
                            <a href="{{route('post',$post->id)}}#new-comment-input"><i
                                    class="fas fa-reply"></i>Reply</a>
                            @endif

                            <div class="a-report">
                                @if(Auth::guest() || $user->id !== $post->user->id)
                                <a data-toggle="modal" data-dismiss="modal" data-target="#modalWelcome">
                                    <i class="fas fa-flag"></i>Report
                                </a>
                                {{-- @else
                                <a data-toggle="modal" data-dismiss="modal" data-target="#modalPostReport">
                                    <i class="fas fa-flag"></i>Report
                                </a> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row align-self-center justify-content-end">
                            @if($post->user != null)
                            <a href="{{route('profile', $post->user->id)}}">
                                <img class="profile-pic-small" id="posterPic" height="35" width="35"
                                    src="{{ asset($post->user->photo) }}" alt="">
                            </a>
                            @endif

                            <span class="px-1 pl-2 align-self-center">{{date('F d, Y', strtotime($post->time_stamp))}}
                                by</span>
                            @if($post->user == null)
                            <a class="align-self-center"> @unknown </a>
                            @else
                            <a class="align-self-center" href={{ route('profile', $post->user->id)}}>
                                <span>@</span>{{$post->user->username}}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-1 px-0 ml-0">
        <div class="row">
            <div class="community-row">
                <a href="{{ route('community', $post->community->id)}}">
                    <h5 class="community-tag mb-0"> {{$post->community->name}}</h5>
                </a>
            </div>
        </div>
    </div>
</div>