@extends('layout')

@section('title', 'Forgot Password')
@section('body-class', 'auth-page')

@section('content')
<section class="signin-section">
    <div class="container">
        <div class="row g-0 auth-row">
            <div class="col-lg-6">
                <div class="auth-cover-wrapper bg-primary-100">
                    <div class="auth-cover">
                        <div class="title text-center">
                            <h1 class="mb-10" style="color: #16a34a;">Forgot Password</h1>
                            <p>Enter your registered email and we will send a reset link.</p>
                        </div>
                        <div class="cover-image text-center">
                            <img src="{{ asset('assets/images/auth/signin-image.svg') }}" alt="forgot password image">
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

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="/forgot-password" method="POST">
                            @csrf

                            <div class="input-style-1">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                            </div>

                            <div class="button-group d-flex justify-content-center flex-wrap">
                                <button type="submit" class="main-btn btn-hover w-100 text-center" style="background-color: #16a34a;">
                                    Send Reset Link
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
