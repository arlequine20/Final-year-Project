@extends('layout')

@section('title', 'Trash')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Trash</h3>
        <p class="text-muted mb-0">Deleted tasks (you can restore or delete permanently)</p>
    </div>
    <a href="/tasks" class="main-btn muted-btn" btn-hover">← Back to Tasks</a>
</div>

@if($tasks->count() > 0)
    <div class="row">
        @foreach($tasks as $task)
            <div class="col-lg-6">
                <div class="page-card">

                    <h5>{{ $task->title }}</h5>

                    <p class="text-muted">
                        {{ $task->description ?? 'No description' }}
                    </p>

                    <p><strong>Team:</strong> {{ $task->team->name ?? 'N/A' }}</p>

                    <div class="d-flex gap-2 mt-3">

                        <!-- RESTORE -->
                        <form action="/tasks/{{ $task->id }}/restore" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                Restore
                            </button>
                        </form>

                        <!-- PERMANENT DELETE -->
                        <form action="/tasks/{{ $task->id }}/force-delete" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete permanently? This cannot be undone!')">
                                Delete Forever
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="page-card">
        <div class="alert alert-info mb-0">
            Trash is empty.
        </div>
    </div>
@endif

@endsection