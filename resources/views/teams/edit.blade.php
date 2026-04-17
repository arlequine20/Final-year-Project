@extends('layout')

@section('title', 'Edit Team')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Edit Team</h3>
        <p class="text-muted mb-0">Update team details.</p>
    </div>

    <a href="/teams" class="btn-manage-members">
        Back to Teams
    </a>
</div>

<div class="page-card">

    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/teams/{{ $team->id }}/update" method="POST">
        @csrf

        <div class="row">

            <!-- TEAM NAME -->
            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Team Name</label>
                    <input type="text" name="name" value="{{ $team->name }}" required>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="col-md-12">
                <div class="input-style-1">
                    <label>Description</label>
                    <textarea name="description" rows="4">{{ $team->description }}</textarea>
                </div>
            </div>

        </div>

        <div class="button-group mt-3">
            <button type="submit" class="main-btn primary-btn btn-hover">
                Update Team
            </button>
        </div>

    </form>

</div>

@endsection