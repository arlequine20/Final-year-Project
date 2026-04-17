@extends('layout')

@section('title', 'Tasks')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="mb-1">All Tasks</h3>
        <p class="text-muted mb-0">Track and manage all assigned tasks.</p>
    </div>
   <div class="d-flex gap-2">

    <a href="/tasks/create" class="main-btn primary-btn btn-hover">
        + Create Task
    </a>

    <a href="/tasks/trash" class="main-btn muted-btn btn-hover">
       🗑️ Trash
    </a>

</div>
</div>

@if($tasks->count() > 0)
    <div class="row">
        @foreach($tasks as $task)
            <div class="col-lg-6">
                <div class="page-card task-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">{{ $task->title }}</h5>

                        @if($task->priority == 'high')
                            <span class="custom-badge priority-high">High Priority</span>
                        @elseif($task->priority == 'medium')
                            <span class="custom-badge priority-medium">Medium Priority</span>
                        @else
                            <span class="custom-badge priority-low">Low Priority</span>
                        @endif
                    </div>

                    <p class="text-muted mb-3">
                        {{ $task->description ?? 'No description provided.' }}
                    </p>

                    <div class="task-meta mb-3">
                        <p class="mb-2"><strong>Team:</strong> {{ $task->team->name ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Assigned To:</strong> {{ $task->assignee->name ?? 'Unassigned' }}</p>
                        <p class="mb-2"><strong>Created By:</strong> {{ $task->creator->name ?? 'Unknown' }}</p>
                        <p class="mb-2">
                            <strong>Due Date:</strong>
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No due date' }}
                        </p>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
                        <div>
                            <strong>Status:</strong>
                            @if($task->status == 'to_do')
                                <span class="custom-badge status-todo">To Do</span>
                            @elseif($task->status == 'doing')
                                <span class="custom-badge status-doing">Doing</span>
                            @else
                                <span class="custom-badge status-done">Done</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
@if(auth()->user()->role === 'admin')
    <!-- ADMIN ACTIONS -->
    <a href="/tasks/{{ $task->id }}/edit" class="btn-edit-custom">Edit</a>

    <form method="POST" action="/tasks/delete/{{ $task->id }}" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn-delete-custom">Delete</button>
    </form>

@else
    <!-- USER ACTIONS -->
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
@endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="page-card text-center py-5">
        <h5 class="mb-2">No Tasks Yet</h5>
        <p class="text-muted mb-3">Start by creating your first task for the team.</p>
        <a href="/tasks/create" class="main-btn primary-btn btn-hover">+ Create First Task</a>
    </div>
@endif
@endsection

@section('scripts')
<style>
    .task-card {
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }

    .task-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.06);
    }

    .custom-badge {
        display: inline-block;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 999px;
    }

    /* Priority */
    .priority-high {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .priority-medium {
        background-color: #fef3c7;
        color: #b45309;
    }

    .priority-low {
        background-color: #dcfce7;
        color: #15803d;
    }

    /* Status */
    .status-todo {
        background-color: #e5e7eb;
        color: #374151;
        margin-left: 8px;
    }

    .status-doing {
        background-color: #dbeafe;
        color: #1d4ed8;
        margin-left: 8px;
    }

    .status-done {
        background-color: #dcfce7;
        color: #15803d;
        margin-left: 8px;
    }

    /* Buttons */
    .btn-edit-custom {
        background-color: #dbeafe;
        color: #1d4ed8;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .btn-edit-custom:hover {
        background-color: #bfdbfe;
        color: #1e40af;
    }

    .btn-delete-custom {
        background-color: #fee2e2;
        color: #b91c1c;
        border: none;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .btn-delete-custom:hover {
        background-color: #fecaca;
        color: #991b1b;
    }

    /* DARK MODE */
    body.dark-mode .task-card {
        border: 1px solid #374151;
    }

    body.dark-mode .task-card:hover {
        box-shadow: 0 12px 30px rgba(0,0,0,0.25);
    }

    body.dark-mode .priority-high {
        background-color: #7f1d1d;
        color: #fee2e2;
    }

    body.dark-mode .priority-medium {
        background-color: #949827;
        color: #fde68a;
    }

    body.dark-mode .priority-low {
        background-color: #14532d;
        color: #dcfce7;
    }

    body.dark-mode .status-todo {
        background-color: #949827;
        color: #f3f4f6;
    }

    body.dark-mode .status-doing {
        background-color: #1e3a8a;
        color: #dbeafe;
    }

    body.dark-mode .status-done {
        background-color: #14532d;
        color: #dcfce7;
    }

    body.dark-mode .btn-edit-custom {
        background-color: #1e3a8a;
        color: #dbeafe;
    }

    body.dark-mode .btn-edit-custom:hover {
        background-color: #2563eb;
        color: white;
    }

    body.dark-mode .btn-delete-custom {
        background-color: #7f1d1d;
        color: #fee2e2;
    }

    body.dark-mode .btn-delete-custom:hover {
        background-color: #991b1b;
        color: white;
    }
</style>
@endsection