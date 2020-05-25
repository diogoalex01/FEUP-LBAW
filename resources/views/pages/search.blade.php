@extends('layouts.app', ['title' =>"Search Results | PearToPear"])
@section('content')
<!-- Page Content -->
<div class="container">

    <div class="row mt-5" style="padding: 20 0;">

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
            {{-- home_post($auth, "someusername", "myProfile.php", "./images/avatar_male.png", "/Porto", "March 5, 2020", 12, 2, "https://s31450.pcdn.co/wp-content/uploads/2017/08/iStock-157735020-170828.jpg", "Problem with studying.", "Hello i am desperately trying to find a way
                                        to learn how to learn. I am
                                        in the first semester of my CS uni and i just realised that i dont know how
                                        to
                                        start
                                        learning a new course. I tried reading the provided book / searching on
                                        internet
                                        but
                                        when it comes to the homework i dont know a single thing...So please if you
                                        have
                                        any
                                        umm
                                        i dont know tip or how to it would be nice. <br>
                                        Thank you.", $admin);  --}}
            @endif
        </div>
    </div>
</div>

@endsection