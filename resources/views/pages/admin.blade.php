@extends('layouts.app', ['title' =>"Admin Dashboard | PearToPear"])
@section('content')

<!-- Page Content -->
<div class="container">
    {{print_r(Auth::guard('admin')->user())}}
    {{-- {{print_r($reports)}} --}}
    <div class="row">
        <!-- Aside -->
        <div class="col-md-3 aside" style="padding-top: 33px;">
            @include('partials.adminMenu')
        </div>

        <!-- Reports Column -->
        <div class="col-md-9">

            <h1 id="current-title" class="mt-4 mb-4 ml-2"> All Requests</h1>

            <!-- User -->
            <div id="user-tab-admin" class="active-tab admin-content">
                <p>user</p>
                {{-- @foreach($users as $user)
                @include('partials.reportPost')
                @endforeach --}}
            </div>

            <!-- Comment -->
            <div id="comment-tab-admin" class="active-tab admin-content">
                <p>comment</p>
                {{-- @foreach($comments as $comment)
                @include('partials.reportPost')
                @endforeach --}}
            </div>

            <!-- Post -->
            <div id="post-tab-admin" class="active-tab admin-content">
                <p>post</p>
                {{-- @foreach($posts as $post)
                @include('partials.reportPost')
                @endforeach --}}
            </div>

            <!-- Community -->
            <div id="community-tab-admin" class="active-tab admin-content">
                <p>communty</p>
                {{-- @foreach($communities as $community)
                @include('partials.reportCommunity')
                @endforeach --}}
            </div>

        </div>
    </div>
</div>

@endsection