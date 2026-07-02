@extends('layout')

@section('title', 'Manager Dashboard')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-1">Manager Dashboard</h2>
        <p class="mb-0 text-muted">Assign tasks to your team, monitor progress, and view reports.</p>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-3 col-md-6">
        <div class="page-card text-center">
            <h6>Team Tasks</h6>
            <h2>{{ $totalTeamTasks }}</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="page-card text-center">
            <h6>To Do</h6>
            <h2>{{ $toDo }}</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="page-card text-center">
            <h6>Doing</h6>
            <h2>{{ $doing }}</h2>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="page-card text-center">
            <h6>Done</h6>
            <h2>{{ $done }}</h2>
        </div>
    </div>
</div>

<div class="page-card mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Team Tasks</h5>
        <a href="/tasks" class="main-btn primary-btn btn-hover">View All Tasks</a>
    </div>

    @if($teamTasks->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Team</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teamTasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->team->name ?? 'N/A' }}</td>
                            <td>{{ $task->assignee->name ?? 'Unassigned' }}</td>
                            <td>
                                @if($task->status == 'to_do')
                                    <span class="badge bg-secondary">To Do</span>
                                @elseif($task->status == 'doing')
                                    <span class="badge bg-primary">Doing</span>
                                @else
                                    <span class="badge bg-success">Done</span>
                                @endif
                            </td>
                            <td>
                                <a href="/tasks/{{ $task->id }}" class="btn-edit-custom">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No tasks for your teams yet.</p>
    @endif
</div>

<div class="page-card mt-4 manager-team-panel">
    <h5>Team Members</h5>
    @if($teamMembers->count() > 0)
        <ul class="list-group list-group-flush">
            @foreach($teamMembers as $member)
                <li class="list-group-item manager-list-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $member->name }}</strong>
                        <span class="text-muted">({{ ucfirst(str_replace('_', ' ', $member->role)) }})</span>
                    </div>
                    @if($member->id === auth()->id())
                        <span class="badge bg-primary">You</span>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No team members found.</p>
    @endif
</div>

<div class="page-card mt-4 manager-team-panel">
    <h5>My Teams</h5>
    @if($teams->count() > 0)
        <ul class="list-group list-group-flush">
            @foreach($teams as $team)
                <li class="list-group-item manager-list-item">
                    <strong>{{ $team->name }}</strong>
                    <div class="text-muted">{{ $team->description ?? 'No description' }}</div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">You are not assigned to any team.</p>
    @endif
</div>

@endsection
