@extends('layout')

@section('title', 'Teams')

@section('content')



<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-1">Teams Management</h2>
        <p class="mb-0 text-muted">View and manage all teams in the system.</p>
    </div>

    <a href="/teams/create" class="main-btn primary-btn btn-hover">
        + Create Team
    </a>
</div>

@if($teams->count() > 0)
    <div class="row">
       @foreach($teams as $team)
    <div class="col-lg-6">
        <div class="page-card team-card h-100">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                <div>
                    <h4 class="mb-1">{{ $team->name }}</h4>
                    <p class="text-muted mb-0">Team Workspace</p>
                </div>
                <span class="badge bg-success">Active</span>
            </div>

            <!-- DESCRIPTION -->
            <p class="mb-3">
                {{ $team->description ?? 'No description provided for this team.' }}
            </p>

            <hr>

            <!-- META -->
            <div class="mb-3">
                <p class="mb-2">
                    <strong>Created By:</strong> {{ $team->creator->name ?? 'Unknown' }}
                </p>

                <p class="mb-0">
                    <strong>Created At:</strong> {{ $team->created_at->format('d M Y, h:i A') }}
                </p>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">

               <a href="/teams/{{ $team->id }}/members" class="btn-manage-members">
    Manage Members
</a>

                <div class="d-flex gap-2">

                    <!-- EDIT BUTTON -->
                    <a href="/teams/{{ $team->id }}/edit" class="btn-edit-custom">
                        Edit
                    </a>

                    <!-- DELETE BUTTON -->
                    <form action="/teams/{{ $team->id }}/delete" method="POST">
                        @csrf
                        <button type="submit" class="btn-delete-custom"
                            onclick="return confirm('Are you sure you want to delete this team?')">
                            Delete
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endforeach
    </div>
@else
    <div class="page-card text-center">
        <h4>No Teams Yet</h4>
        <p class="text-muted">Start by creating your first collaboration team.</p>
        <a href="/teams/create" class="main-btn primary-btn btn-hover mt-2">
            + Create First Team
        </a>
    </div>
@endif

@endsection