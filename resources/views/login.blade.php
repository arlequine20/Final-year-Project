

@extends('layout')

@section('title', 'Login')
@section('body-class', 'auth-page')

@section('content')
<section class="signin-section">
    <div class="container">
        <div class="row g-0 auth-row">
            <div class="col-lg-6">
                <div class="auth-cover-wrapper bg-primary-100">
                    <div class="auth-cover">
                        <div class="title text-center">
                           <h1 class="mb-10" style="color: #16a34a;">CollabTrack</h1>
<p>
    Login to manage teams, tasks, workflow and reports.
</p>
                        </div>
                        <div class="cover-image text-center">
                            <img src="{{ asset('assets/images/auth/signin-image.svg') }}" alt="login image">
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
                        <h6 class="mb-15">Sign In</h6>
                        <p class="text-sm mb-25">
                            Enter your email and password to continue.
                        </p>

                       
                        <form method="POST" action="/login">
                            @csrf

                            <div class="input-style-1">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter your email" required>
                            </div>

          <div class="input-style-1">
    <label>Password</label>
    <div style="position: relative;">
        <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required>
        <span onclick="toggleLoginPassword()" style="position: absolute; right: 15px; top: 12px; cursor: pointer;">
            👁
        </span>
    </div>
</div>

                            <div class="button-group d-flex justify-content-center flex-wrap">
                                <button type="submit" class="main-btn  btn-hover w-100 text-center" style="background-color: #16a34a;">
                                    Sign In
                                </button>
                            </div>
                        </form>

                        <div class="singin-option pt-40">
                            <p class="text-sm text-medium text-dark text-center mb-2">
                                <a href="/forgot-password" style="color: #16a34a;">Forgot your password?</a>
                            </p>
                            <p class="text-sm text-medium text-dark text-center">
                                Don’t have an account?
                                <a href="/register" style="color: #16a34a;">Create one</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection