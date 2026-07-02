@extends('layout')

@section('title', 'Create Team')

@section('content')

<div class="topbar">
    <h2 class="mb-1">Create New Team</h2>
    <p class="mb-0 text-muted">Set up a new team and prepare collaboration spaces.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="page-card">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/teams/store" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Team Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter team name" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" rows="5" class="form-control" placeholder="Enter team description"></textarea>
                </div>
                <div class="input-style-1">

<label>Select Manager</label>

<select name="manager_id" class="form-control" required>

<option value="">
Choose Manager
</option>


@foreach($managers as $manager)

<option value="{{ $manager->id }}">

{{ $manager->name }}

</option>

@endforeach


</select>

</div>

                <div class="d-flex gap-2">
                    <button type="submit" class="main-btn primary-btn btn-hover">
                        Create Team
                    </button>

                    <a href="/teams" class="main-btn light-btn btn-hover">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection