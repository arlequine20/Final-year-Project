@extends('layout')

@section('title', 'Tasks')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h3 class="mb-1">All Tasks</h3>
        <p class="text-muted mb-0">Track and manage all assigned tasks.</p>
    </div>

    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'admin')
            <a href="/tasks/create" class="main-btn primary-btn btn-hover">
                + Create Task
            </a>

            <a href="/tasks/trash" class="main-btn muted-btn btn-hover">
                🗑️ Trash
            </a>
        @endif
    </div>
</div>

@if($tasks->count() > 0)
<div class="row">

@foreach($tasks as $task)
<div class="col-lg-6">
<div class="page-card task-card h-100">

    <!-- TITLE -->
    <div class="mb-2">
        <h5 class="mb-0">{{ $task->title }}</h5>
    </div>

    <!-- PRIORITY + DEADLINE + APPROVAL -->
    <div class="d-flex gap-2 flex-wrap mb-3">

        <!-- PRIORITY -->
        @if($task->priority == 'high')
            <span class="custom-badge priority-high">High Priority</span>
        @elseif($task->priority == 'medium')
            <span class="custom-badge priority-medium">Medium Priority</span>
        @else
            <span class="custom-badge priority-low">Low Priority</span>
        @endif

        <!-- DEADLINE BADGES -->
        @php
            $due = $task->due_date ? \Carbon\Carbon::parse($task->due_date) : null;
            $today = \Carbon\Carbon::today();
        @endphp

        @if($due && $task->status != 'done')

            @if($due->isPast())
                <span class="badge bg-dark">❌ Overdue</span>

            @elseif($today->diffInDays($due) <= 1)
                <span class="badge bg-danger">⚠️ Due Soon</span>

            @elseif($today->diffInDays($due) <= 3)
                <span class="badge bg-warning text-dark">⏳ Upcoming</span>

            @endif

        @endif

        <!-- APPROVAL STATUS -->
        @php
            $status = $task->approval_status ?? 'pending';
        @endphp

        @if($status == 'approved')
            <span class="badge bg-success">✅ Approved</span>
        @elseif($status == 'rejected')
            <span class="badge bg-danger">❌ Rejected</span>
        @else
            <span class="badge bg-secondary">⏳ Pending</span>
        @endif

    </div>

    <!-- APPROVAL BUTTONS (UNDER PRIORITY) -->
    @if(auth()->user()->role === 'admin' && $status == 'pending' && $task->status == 'done' && $task->created_by == auth()->id())
        <div class="mb-3 d-flex gap-2">

            <form method="POST" action="/tasks/{{ $task->id }}/approve">
                @csrf
                @method('PATCH')
                <button class="btn btn-success btn-sm">✅ Approve</button>
            </form>

            <form method="POST" action="/tasks/{{ $task->id }}/reject">
                @csrf
                @method('PATCH')
                <button class="btn btn-danger btn-sm">❌ Reject</button>
            </form>

        </div>
    @endif

    <!-- MANAGER REASSIGNMENT -->
    @if(auth()->user()->role === 'manager' && $managerTeams->count() > 0)
        <div class="mb-3">
            <form method="POST" action="/tasks/{{ $task->id }}/update-assignment">
                @csrf
                @method('PATCH')
                @php
                    $selectedTeamId = $task->team_id && $managerTeams->contains('id', $task->team_id)
                        ? $task->team_id
                        : optional($managerTeams->first())->id;
                    $selectedTeam = $managerTeams->firstWhere('id', $selectedTeamId);
                @endphp
                <div class="d-flex gap-2 flex-wrap">
                    <select name="team_id" class="form-control form-control-sm manager-team-select" required>
                        @foreach($managerTeams as $team)
                            <option value="{{ $team->id }}" {{ $selectedTeamId == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                    <select name="assigned_to" class="form-control form-control-sm manager-member-select">
                        <option value="">No member yet</option>
                        @foreach($selectedTeam->users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm">Assign</button>
                </div>
            </form>
        </div>
    @endif

    <!-- DESCRIPTION -->
    <p class="text-muted mb-3">
        {{ $task->description ?? 'No description provided.' }}
    </p>

    <!-- META -->
    <div class="task-meta mb-3">
        <p class="mb-2"><strong>Team:</strong> {{ $task->team->name ?? 'N/A' }}</p>
        <p class="mb-2"><strong>Assigned To:</strong> {{ $task->assignee->name ?? 'Unassigned' }}</p>
        <p class="mb-2"><strong>Created By:</strong> {{ $task->creator->name ?? 'Unknown' }}</p>
        <p class="mb-2">
            <strong>Due Date:</strong>
            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'No due date' }}
        </p>
    </div>

    <!-- STATUS + ACTIONS -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">

        <!-- STATUS -->
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

        <!-- ACTIONS -->
        <div class="d-flex gap-2 flex-wrap">

            <a href="/tasks/{{ $task->id }}" class="btn-manage-members">
                View
            </a>

            @if(auth()->user()->role === 'admin' && $task->created_by == auth()->id())

                <a href="/tasks/{{ $task->id }}/edit" class="btn-edit-custom">Edit</a>

                <form method="POST" action="/tasks/{{ $task->id }}/delete">
                    @csrf
                    @method('DELETE')
                    <button class="btn-delete-custom">Delete</button>
                </form>

            @elseif(auth()->user()->role === 'manager')
                <!-- Manager can only reassign, no edit/delete -->

            @else

                <form method="POST" action="/tasks/update-status/{{ $task->id }}">
                    @csrf
                    @method('PATCH')

                    @if($task->status == 'to_do')
                        <button class="btn-edit-custom">Start →</button>

                    @elseif($task->status == 'doing')
                        <button class="btn-edit-custom">Complete →</button>

                    @elseif($task->status == 'done')
                        <button class="btn-delete-custom"
                            onclick="return confirm('Reopen this task?')">
                            Reopen
                        </button>
                    @endif

                </form>

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
    <p class="text-muted mb-3">Start by creating your first task.</p>

    @if(auth()->user()->role === 'admin')
        <a href="/tasks/create" class="main-btn primary-btn btn-hover">
            + Create First Task
        </a>
    @endif
</div>

@endif

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
