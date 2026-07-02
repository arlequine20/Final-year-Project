@extends('layout')

@section('title', 'Task Details')

@section('content')
@php
    $managerTeams = auth()->user()->role === 'manager'
        ? auth()->user()->teams()->with('users')->get()
        : collect();
    $selectedTeamId = $task->team_id && $managerTeams->contains('id', $task->team_id)
        ? $task->team_id
        : optional($managerTeams->first())->id;
    $selectedTeam = $managerTeams->firstWhere('id', $selectedTeamId);
@endphp

<div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="mb-1">{{ $task->title }}</h3>
        <p class="text-muted mb-0">Task Details Overview</p>
    </div>

    <a href="/tasks" class="main-btn muted-btn btn-hover">Back to Tasks</a>
</div>

<div class="row mt-3">
    <div class="col-lg-8">
        <div class="page-card">
            <h5 class="mb-3">Description</h5>
            <p class="text-muted">{{ $task->description ?? 'No description provided.' }}</p>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Team:</strong>
                    <p>{{ $task->team->name ?? 'Unassigned' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Assigned To:</strong>
                    <p>{{ $task->assignee->name ?? 'Unassigned' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Created By:</strong>
                    <p>{{ $task->creator->name ?? 'Unknown' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Due Date:</strong>
                    <p>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No due date' }}</p>
                </div>
            </div>

            @if($task->attachment)
                <div class="mt-3">
                    <strong>Attachment:</strong><br>
                    <a href="{{ asset('storage/' . $task->attachment) }}" target="_blank" class="btn-edit-custom mt-2">
                        View File
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <form method="POST" action="/tasks/{{ $task->id }}/delete-attachment" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button class="btn-delete-custom">Remove Attachment</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>

        <div class="page-card mt-4">
            <h5 class="mb-3">Comments</h5>

            <form method="POST" action="/tasks/{{ $task->id }}/comments" class="mb-4">
                @csrf
                <textarea name="content" class="form-control mb-2" rows="3" placeholder="Write a comment..." required></textarea>
                <button class="main-btn primary-btn btn-hover">Post Comment</button>
            </form>

            @forelse($task->comments as $comment)
                <div class="mb-3 p-3 comment-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>

                        @if($comment->user_id === auth()->id() || auth()->user()->role === 'admin')
                            <form method="POST" action="/comments/{{ $comment->id }}">
                                @csrf
                                @method('DELETE')
                                <button class="comment-delete-btn" onclick="return confirm('Delete this comment?')">Delete</button>
                            </form>
                        @endif
                    </div>

                    <p class="mb-0 mt-2">{{ $comment->content }}</p>
                </div>
            @empty
                <p class="text-muted">No comments yet.</p>
            @endforelse
        </div>

        @if(auth()->user()->role === 'team_member' && $task->assigned_to == auth()->id())
            <div class="page-card mt-4">
                <h5 class="mb-3">Update Progress</h5>

                <form method="POST" action="/tasks/{{ $task->id }}/progress" enctype="multipart/form-data">
                    @csrf
                    <textarea name="progress_note" class="form-control mb-2" placeholder="Describe your progress...">{{ $task->progress_note }}</textarea>
                    <input type="file" name="progress_file" class="form-control mb-2">
                    <button class="main-btn primary-btn">Save Progress</button>
                </form>
            </div>
        @endif

        <div class="page-card mt-4">
            <h5 class="mb-3">Current Progress</h5>

            @if($task->progress_note)
                <p>{{ $task->progress_note }}</p>
            @endif

            @if($task->progress_file)
                <a href="{{ asset('storage/' . $task->progress_file) }}" target="_blank">View Progress File</a>

                @if($task->assigned_to == auth()->id() || auth()->user()->role === 'admin')
                    <form method="POST" action="/tasks/{{ $task->id }}/delete-progress" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete-custom">Remove Progress</button>
                    </form>
                @endif
            @else
                <p class="text-muted">No progress uploaded yet.</p>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="page-card mb-3 text-center">
            <h6>Status</h6>
            @if($task->status == 'to_do')
                <span class="badge bg-secondary">To Do</span>
            @elseif($task->status == 'doing')
                <span class="badge bg-primary">Doing</span>
            @else
                <span class="badge bg-success">Done</span>
            @endif
        </div>

        <div class="page-card mb-3 text-center">
            <h6>Priority</h6>
            @if($task->priority == 'high')
                <span class="badge bg-danger">High</span>
            @elseif($task->priority == 'medium')
                <span class="badge bg-warning text-dark">Medium</span>
            @else
                <span class="badge bg-success">Low</span>
            @endif

            <div class="mt-2">
                @if($task->approval_status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($task->approval_status == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @else
                    <span class="badge bg-secondary">Pending Approval</span>
                @endif
            </div>
        </div>

        @if(auth()->user()->role === 'manager' && $managerTeams->count() > 0)
            <div class="page-card mb-3">
                <h6 class="mb-3">Assign Task</h6>

                <form action="/tasks/{{ $task->id }}/update-assignment" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label>Team</label>
                        <select name="team_id" class="form-control manager-team-select" required>
                            @foreach($managerTeams as $team)
                                <option value="{{ $team->id }}" {{ $selectedTeamId == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Assign To</label>
                        <select name="assigned_to" class="form-control manager-member-select">
                            <option value="">No member yet</option>
                            @foreach($selectedTeam->users ?? [] as $user)
                                <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="main-btn primary-btn btn-hover w-100">
                        Save Assignment
                    </button>
                </form>
            </div>
        @endif

        <div class="page-card text-center">
            <h6 class="mb-3">Actions</h6>

            @if(auth()->user()->role === 'admin' && $task->created_by == auth()->id())
                <a href="/tasks/{{ $task->id }}/edit" class="btn-edit-custom w-100 mb-2">Edit Task</a>

                <form method="POST" action="/tasks/{{ $task->id }}/delete">
                    @csrf
                    @method('DELETE')
                    <button class="btn-delete-custom w-100" onclick="return confirm('Delete this task?')">
                        Delete Task
                    </button>
                </form>
            @elseif(auth()->user()->role === 'team_member' && $task->assigned_to == auth()->id())
                <form method="POST" action="/tasks/update-status/{{ $task->id }}">
                    @csrf
                    @method('PATCH')

                    @if($task->status == 'to_do')
                        <button class="btn-edit-custom w-100">Start Task</button>
                    @elseif($task->status == 'doing')
                        <button class="btn-edit-custom w-100">Mark as Done</button>
                    @elseif($task->status == 'done')
                        <button class="btn-reopen" onclick="return confirm('Reopen this task?')">
                            Reopen Task
                        </button>
                    @endif
                </form>
            @else
                <p class="text-muted mb-0">No actions available.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@if(auth()->user()->role === 'manager')
    @section('scripts')
    <script>
        const managerTeams = @json($managerTeams->values());

        document.querySelectorAll('.manager-team-select').forEach(teamSelect => {
            teamSelect.addEventListener('change', function () {
                const form = this.closest('form');
                const memberSelect = form.querySelector('.manager-member-select');
                const selectedTeam = managerTeams.find(team => team.id == this.value);

                memberSelect.innerHTML = '<option value="">No member yet</option>';

                if (!selectedTeam || !selectedTeam.users) {
                    return;
                }

                selectedTeam.users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = user.name;
                    memberSelect.appendChild(option);
                });
            });
        });
    </script>
    @endsection
@endif
