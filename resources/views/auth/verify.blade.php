@extends('layouts.app', ['title' => "Verify E-mail Address"])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="">
                <h1 class="mt-3">Verify Your E-mail Address</h1>
                <hr class="mb-0">

                <div class="mt-2 card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{-- {{ __('If you did not receive the email') }}, <a href="{{ url('.') }}">{{ __('click here to request another') }}</a>. --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
