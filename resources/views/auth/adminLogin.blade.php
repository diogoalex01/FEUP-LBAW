@extends('layouts.admin', ['title' => "Admin Login"])

@section('content')

<div class="container col-md-6 col-lg-4 pt-5">
    <h2 class="text-center text-dark title-padding title-mobile mb-4">Admin Login</h2>


    <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
        {{ csrf_field() }}

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="my-auto">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row d-flex justify-content-lg-center">
            <div class="col-lg-10 mb-4">

                <div id="feedback-message-login">
                </div>

                <label for="emailLogin">Email</label>
                <input id="emailLogin" name="email" type="email" class="form-control" placeholder="Email" required>

            </div>
            <div class="col-lg-10 mty-3">
                <label for="passwordLogin">Password</label>
                <input type="password" id="passwordLogin" name="password" class="form-control"
                    pattern="(?=.*\d)(?=.*[a-zA-Z]).{6,}" placeholder="Password" required>

            </div>
            <div class="form-group row login-signup-trans mt-4 mx-0 w-100">
                <button type="submit" class="btn btn-outline-dark ">Log in</button>
            </div>
            {{-- <div>
            <button id="loginBtn" type="button" data-dismiss="modal" data-toggle="modal" data-target="#modalRecover"
                class="my-auto btn">Forgot password?
            </button>
        </div> --}}
        </div>
    </form>
</div>

@endsection