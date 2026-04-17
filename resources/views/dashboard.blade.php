@extends('layout')

@section('title', 'Dashboard')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h2 class="mb-1">Welcome, {{ auth()->user()->name }}</h2>
        <p class="mb-0 text-muted">
            Role: <strong>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</strong>
        </p>
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

    <!-- NEW CARD -->
    <div class="col-lg-3 col-md-6 mt-3">
        <div class="page-card text-center">
            <h6>High Priority ⚠️</h6>
            <h2>{{ $highPriority }}</h2>
        </div>
    </div>

</div>

<!-- RECENT TASKS -->
<div class="page-card mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Recent Tasks</h5>
        <a href="/tasks" class="main-btn light-btn btn-hover">View All</a>
    </div>

    @if($recentTasks->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>

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
                                @if($task->priority == 'high')
                                    <span class="badge bg-danger">High</span>
                                @elseif($task->priority == 'medium')
                                    <span class="badge bg-warning text-dark">Medium</span>
                                @else
                                    <span class="badge bg-success">Low</span>
                                @endif
                            </td>

                            <td>{{ $task->due_date ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info mb-0">
            No tasks yet.
        </div>
    @endif
</div>

@endsection