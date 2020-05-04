@extends('layouts.app', ['title' => "Not Found"])
@section('content')
<div id="error404" class="my-auto">
    <div class = "col justify-self-center">
        <div class = "text-center">
            <img class="notification-pic" id="login" height="320" width="420"
            src="{{ asset('img/404img.png') }}" alt="Profile Image">
        </div>
        <div class = "text-center error-header">
            <h1 class="">404</h1>
            <h2 class="">Oh snap! Looks like we're missing a piece.</h2>
        </div>
    </div>
</div>
@endsection