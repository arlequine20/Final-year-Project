@extends('layout')

@section('title', 'Reports')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Reports Dashboard</h3>
        <p class="text-muted mb-0">Overview of system performance</p>
    </div>

    <a href="/tasks" class="main-btn secondary-btn btn-hover">
        Back to Tasks
    </a>
</div>

<div class="row">

    <div class="col-md-3">
        <div class="page-card text-center">
            <h5>Total Tasks</h5>
            <h2>{{ $totalTasks }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="page-card text-center">
            <h5>To Do</h5>
            <h2>{{ $toDo }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="page-card text-center">
            <h5>Doing</h5>
            <h2>{{ $doing }}</h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="page-card text-center">
            <h5>Done</h5>
            <h2>{{ $done }}</h2>
        </div>
    </div>

</div>

<div class="row mt-4">
    <div class="col-md-3">
        <div class="page-card text-center">
            <h5>High Priority</h5>
            <h2>{{ $highPriority }}</h2>
        </div>
    </div>
</div>

<div class="page-card mt-4">
    <h5 class="mb-3">Tasks Per Team</h5>

    <table class="table">
        <thead>
            <tr>
                <th>Team</th>
                <th>Total Tasks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasksPerTeam as $item)
                <tr>
                    <td>{{ $item->team->name ?? 'N/A' }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection