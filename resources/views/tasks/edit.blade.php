@extends('layout')

@section('title', 'Edit Task')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Edit Task</h3>
        <p class="text-muted mb-0">Update task details and assignment.</p>
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

    <form action="/tasks/{{ $task->id }}/update" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Task Title</label>
                    <input type="text" name="title" value="{{ $task->title }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Team</label>
                    <select name="team_id" id="teamSelect" class="form-control" required>
                        <option value="">Select Team</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $task->team_id == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-style-1">
                    <label>Description</label>
                    <textarea name="description" rows="5">{{ $task->description }}</textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Assign To</label>
                    <select name="assigned_to" id="memberSelect" class="form-control">
                        <option value="">Select Member</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                        <option value="doing" {{ $task->status == 'doing' ? 'selected' : '' }}>Doing</option>
                        <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Priority</label>
                    <select name="priority" class="form-control" required>
                        <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="{{ $task->due_date }}">
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

{{-- Hidden JSON data for JS --}}
<script type="application/json" id="teamsData">
    {!! json_encode($teams) !!}
</script>

<script type="application/json" id="assignedUserData">
    {!! json_encode($task->assigned_to) !!}
</script>
@endsection

@section('scripts')
<script>
    const teams = JSON.parse(document.getElementById('teamsData').textContent);
    const selectedAssignedUser = JSON.parse(document.getElementById('assignedUserData').textContent);

    const teamSelect = document.getElementById('teamSelect');
    const memberSelect = document.getElementById('memberSelect');

    function loadMembers(teamId) {
        memberSelect.innerHTML = '<option value="">Select Member</option>';

        if (!teamId) return;

        const selectedTeam = teams.find(team => team.id == teamId);

        if (selectedTeam && selectedTeam.users && selectedTeam.users.length > 0) {
            selectedTeam.users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.name + ' (' + user.role.replace('_', ' ') + ')';

                if (user.id == selectedAssignedUser) {
                    option.selected = true;
                }

                memberSelect.appendChild(option);
            });
        }
    }

    if (teamSelect && memberSelect) {
        teamSelect.addEventListener('change', function () {
            loadMembers(this.value);
        });

        window.addEventListener('DOMContentLoaded', function () {
            loadMembers(teamSelect.value);
        });
    }
</script>
@endsection