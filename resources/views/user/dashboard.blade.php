@extends('layout')

@section('title', 'My Dashboard')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-1">Welcome, {{ auth()->user()->name }}</h2>
        <p class="mb-0 text-muted">Here’s an overview of your tasks</p>
    </div>
</div>

<!-- STATS -->
<div class="row mt-3">

    <div class="col-lg-3 col-md-6">
        <div class="page-card text-center">
            <h6>Total Tasks</h6>
            <h2>{{ $totalTasks }}</h2>
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

<!-- RECENT TASKS -->
<div class="page-card mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">My Recent Tasks</h5>
        <a href="/tasks" class="primary-btn px-3 py-2 rounded">View All</a>
    </div>

    @if($recentTasks->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>

                            <!-- STATUS -->
                            <td>
                                @if($task->status == 'to_do')
                                    <span class="badge bg-secondary">To Do</span>
                                @elseif($task->status == 'doing')
                                    <span class="badge bg-primary">Doing</span>
                                @else
                                    <span class="badge bg-success">Done</span>
                                @endif
                            </td>

                            <!-- PRIORITY -->
                            <td>
                                @if($task->priority == 'high')
                                    <span class="badge bg-danger">High</span>
                                @elseif($task->priority == 'medium')
                                    <span class="badge bg-warning text-dark">Medium</span>
                                @else
                                    <span class="badge bg-success">Low</span>
                                @endif
                            </td>

                            <!-- DUE DATE -->
                            <td>
                                @if($task->due_date)
                                    @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'done')
                                        <span class="text-danger fw-bold">
                                            {{ $task->due_date }} ⚠ Overdue
                                        </span>
                                    @else
                                        {{ $task->due_date }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>

                            <!-- ACTION -->
                            <td>
                                @if($task->status != 'done')
                                    <form method="POST" action="/tasks/update-status/{{ $task->id }}">
                                        @csrf
                                        @method('PATCH')

                                        <button class="btn-edit-custom">
                                            Next Step →
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success fw-bold">✔ Completed</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info mb-0">
            You don’t have any tasks yet.
        </div>
    @endif
</div>
<!-- ✅ TEAM TASKS -->
<div class="page-card mt-4">
    <h5 class="mb-3">Team Tasks</h5>

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

                            <!-- ✅ VIEW BUTTON -->
                            <td>
                                <a href="/tasks/{{ $task->id }}" class="btn-edit-custom">
                                    View Task
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">No team tasks available.</p>
    @endif
</div>
<!-- ✅ TEAM MEMBERS SECTION -->
<div class="page-card mt-4">
    <h5 class="mb-3">My Team Members</h5>

    @if($teams->count() > 0)
        @foreach($teams as $team)
            <div class="mb-3">
                <strong>{{ $team->name }}</strong>

                <ul class="mt-2">
                    @foreach($team->users as $member)
                       <li>
    <strong>{{ $member->name }}</strong>
    
    <span class="text-muted">
        ({{ ucfirst(str_replace('_', ' ', $member->role)) }})
    </span>

    @if($member->id === auth()->id())
        <span class="text-primary">(You)</span>
    @endif
</li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    @else
        <p class="text-muted">You are not part of any team.</p>
    @endif
</div>

@endsection