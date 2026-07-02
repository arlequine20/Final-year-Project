@extends('layout')

@section('title', 'Reset Password')
@section('body-class', 'auth-page')

@section('content')
<section class="signin-section">
    <div class="container">
        <div class="row g-0 auth-row">
            <div class="col-lg-6">
                <div class="auth-cover-wrapper bg-primary-100">
                    <div class="auth-cover">
                        <div class="title text-center">
                            <h1 class="mb-10" style="color: #16a34a;">Reset Password</h1>
                            <p>Create a secure new password for your account.</p>
                        </div>
                        <div class="cover-image text-center">
                            <img src="{{ asset('assets/images/auth/signup-image.svg') }}" alt="reset password image">
                        </div>
                        <div class="shape-image">
                            <img src="{{ asset('assets/images/auth/shape.svg') }}" alt="shape">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="signin-wrapper">
                    <div class="form-wrapper">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/reset-password" method="POST">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="input-style-1">
                                <label>New Password</label>
                                <input type="password" name="password" placeholder="Enter new password" required>
                            </div>

                            <div class="input-style-1">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="Confirm password" required>
                            </div>

                            <div class="button-group d-flex justify-content-center flex-wrap">
                                <button type="submit" class="main-btn btn-hover w-100 text-center" style="background-color: #16a34a;">
                                    Reset Password
                                </button>
                            </div>
                        </form>

                        <div class="singin-option pt-30 text-center">
                            <a href="/login" class="text-sm text-medium" style="color: #16a34a;">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
