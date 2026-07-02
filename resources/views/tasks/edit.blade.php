@extends('layout')

@section('title', 'Edit Task')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Edit Task</h3>
        <p class="text-muted mb-0">Update task details. Managers handle team assignment.</p>
    </div>
    <a href="/tasks" class="main-btn secondary-btn btn-hover">Back to Tasks</a>
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

    <form action="/tasks/{{ $task->id }}/update" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Task Title</label>
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Due Date</label>
                    <input
                        type="date"
                        name="due_date"
                        class="form-control"
                        value="{{ old('due_date', $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '') }}"
                    >
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-style-1">
                    <label>Description</label>
                    <textarea name="description" rows="5">{{ old('description', $task->description) }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Priority</label>
                    <select name="priority" class="form-control" required>
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="to_do" {{ old('status', $task->status) === 'to_do' ? 'selected' : '' }}>To Do</option>
                        <option value="doing" {{ old('status', $task->status) === 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="done" {{ old('status', $task->status) === 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-style-1">
                    <label>Attachment</label>
                    <input type="file" name="attachment" class="form-control">

                    @if($task->attachment)
                        <p class="mt-2">
                            Current File:
                            <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank">
                                View Attachment
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="button-group d-flex justify-content-start flex-wrap mt-3">
            <button type="submit" class="main-btn primary-btn btn-hover">
                Update Task
            </button>
        </div>
    </form>
</div>
@endsection
