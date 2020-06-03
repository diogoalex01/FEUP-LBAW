@extends( (Auth::guard('admin')->check()) ? 'layouts.admin' : 'layouts.app', [ 'title' => "PearToPear | Home"])
@section('content')

<!-- Page Content -->
<div class="container">

    <div class="row" style="padding: 20px 0px;">

        <!-- Aside -->
        <div class="col-md-3 aside mb-4">
            @include('partials.categories')
        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <div id="feedback-message-home">
            </div>

            @if (Auth::check())

            <!-- New Post -->
            <a href={{route('new_post')}}>
                <div class="mt-4 mt-md-1 card mb-4 mr-md-2 mr-lg-4 post-container">
                    <div class="card-body">
                        <div class="row" style="font-size: 0.45rem;">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Write your own post">
                            </div>
                            <div class="col-1 pl-0 my-auto">
                                <i class="fas fa-plus-circle fa-4x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>

            @endif

            <div id="posts-column-home">
                @foreach($posts as $post)
                @include('partials.homePost', ['post'=>$post, 'user'=>$user])
                @endforeach
            </div>

            <div id="no-content" class="card mb-4 mr-md-2 mr-lg-4 post-container" style="display: none;">

                <h5 class="card-header aside-container-top d-flex align-items-center">

                    <div class="col-1 pr-lg-0">
                        <i class="far fa-laugh-beam fa-2x"></i>
                    </div>
                    <div class="col pl-lg-0">
                        Welcome!
                    </div>

                </h5>

                <div class="card-body justify-content-start">
                    <p class="card-text">This will soon be your personalized feed! <br> Explore the platform, join
                        communities and follow other users! Happy reading!
                    </p>
                </div>

            </div>

            <div class="d-flex justify-content-center col-md-11 mt-2">
                <div id="loader" class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection