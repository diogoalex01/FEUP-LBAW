@extends('layouts.app', ['title' =>"Search Results | PearToPear"])
@section('content')
<!-- Page Content -->
<div class="container">

    <div class="row mt-5" style="padding: 20px 0px;">

        <!-- Aside -->
        <div class="col-md-3 aside ">
            <!-- My Categories -->
            @include('partials.categories')
        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            @if(empty($communities) && empty($posts) && empty($postComments))
            @include('partials.noResultsFound')
            @else
            @foreach($communities as $community)
            @include('partials.myProfileCommunity', ['community'=>$community, 'user'=>$user])
            @endforeach

            <!-- Post -->
            <div id="posts-column-home">
                @foreach($posts as $post)
                @include('partials.homePost', ['post'=>$post, 'user'=>$user])
                @endforeach
            </div>

            <!-- Post Comments -->
            <div id="posts-column-home">
                @foreach($postComments as $postComment)
                @include('partials.homePost', ['post'=>$postComment, 'user'=>$user])
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

@endsection