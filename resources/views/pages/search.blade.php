@extends('layouts.app', ['title' =>"Search Results | PearToPear"])
@section('content')
<!-- Page Content -->
<div class="container">

    <div class="row mt-5" style="padding: 20px 0px;">

        <!-- Aside -->
        <div class="col-md-3 aside mb-4">
            @include('partials.adminMenu')
        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <h1><i class="fas fa-search mr-2"></i> Search Results for "<span class ="font-weight-bold search-title-query">{{$query}}</span>"... </h1>
            <hr>

            @if(empty($memberUsers) && empty($communities) && empty($posts) && empty($postComments))
            @include('partials.noResultsFound')
            @else
            @foreach($memberUsers as $memberUser)
            @include('partials.searchUser', ['member'=>$memberUser, 'user'=>$user])
            @endforeach

            @foreach($communities as $community)
            @include('partials.myProfileCommunity', ['community'=>$community, 'user'=>$user])
            @endforeach

            <!-- Post -->
            <div id="posts-column-home">
                @foreach($posts as $post)
                @include('partials.myProfilePost', ['post'=>$post, 'user'=>$user])
                @endforeach
            </div>

            <!-- Post Comments -->
            <div id="posts-column-home">
                @foreach($postComments as $postComment)
                @include('partials.myProfilePost', ['post'=>$postComment, 'user'=>$user])
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>

@endsection