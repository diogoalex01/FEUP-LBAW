@extends('layouts.app', ['title' =>"Search Results for \"{$query}\" | PearToPear"])
@section('content')
<!-- Page Content -->
<div class="container">

    <div class="row mt-5" style="padding: 20px 0px;">

        <!-- Aside -->
        <div class="col-md-3 aside mb-4">
            @include('partials.filterMenu')
        </div>

        <!-- Posts Column -->
        <div class="col-md-9">

            <h1><i class="fas fa-search mr-2"></i> Search Results for "<span
                    class="font-weight-bold search-title-query">{{$query}}</span>"... </h1>
            <hr>

            @foreach($memberUsers as $memberUser)
            @include('partials.searchUser', ['member'=>$memberUser])
            @endforeach

            @foreach($communities as $community)
            @include('partials.myProfileCommunity', ['community'=>$community])
            @endforeach

            <!-- Post -->
            @foreach($posts as $post)
            @include('partials.myProfilePost', ['post'=>$post])
            @endforeach

            <!-- Post Comments -->
            @foreach($comments as $comment)
            @include('partials.myProfileComment', ['comment'=>$comment])
            @endforeach

            @include('partials.noResults')

        </div>
    </div>
</div>

@endsection