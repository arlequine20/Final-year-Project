@extends('layout')

@section('title', 'Register')

@section('body-class', 'auth-page')

@section('content')
<section class="signin-section">
    <div class="container">
        <div class="row g-0 auth-row">
            <div class="col-lg-6">
                <div class="auth-cover-wrapper bg-primary-100">
                    <div class="auth-cover">
                        <div class="title text-center">
                           <h1 class="mb-10" style="color: #16a34a;">Create Account</h1>
<p>
    Register to join your smart collaboration workspace.
</p>
                        </div>
                        <div class="cover-image text-center">
                            <img src="{{ asset('assets/images/auth/signup-image.svg') }}" alt="register image">
                        </div>
                        <div class="shape-image">
                            <img src="{{ asset('assets/images/auth/shape.svg') }}" alt="shape">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="signup-wrapper">
                    <div class="form-wrapper">
                        <h6 class="mb-15">Sign Up</h6>
                        <p class="text-sm mb-25">
                            Create your account to get started.
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="/register">
                            @csrf

                            <div class="input-style-1">
                                <label>Full Name</label>
                                <input type="text" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="input-style-1">
           <label>Role</label>
           <select name="role" class="form-control" required>
        <option value="">Select Role</option>
        <option value="admin">Admin</option>
        <option value="manager">Manager</option>
        <option value="team_member">Team Member</option>
    </select>
</div>

                            <div class="input-style-1">
                                <label>Email</label>
                                <input type="email" name="email" placeholder="Enter your email" required>
                            </div>

   <div class="input-style-1">
    <label>Password</label>
    <div style="position: relative;">
        <input type="password" name="password" id="registerPassword" placeholder="Enter your password" required>
        <span onclick="toggleRegisterPassword()" style="position: absolute; right: 15px; top: 12px; cursor: pointer;">
            👁
        </span>
    </div>
</div>

                            <div class="input-style-1">
    <label>Confirm Password</label>
    <div style="position: relative;">
        <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Confirm your password" required>
        <span onclick="toggleConfirmPassword()" style="position: absolute; right: 15px; top: 12px; cursor: pointer;">
            👁
        </span>
    </div>
</div>

                            <div class="button-group d-flex justify-content-center flex-wrap">
                                <button type="submit" class="main-btn  btn-hover w-100 text-center" style="background-color: #16a34a;">
                                    Sign Up
                                </button>
                            </div>
                        </form>

                        <div class="singin-option pt-40">
                            <p class="text-sm text-medium text-dark text-center">
                                Already have an account?
                                <a href="/login" style="color: #16a34a;">Sign In</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection