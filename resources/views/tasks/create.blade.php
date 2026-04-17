@extends('layout')

@section('title', 'Create Task')

@section('content')
<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Create New Task</h3>
        <p class="text-muted mb-0">Assign tasks to team members and track progress.</p>
    </div>
    <a href="/tasks" class="main-btn secondary-btn btn-hover">View Tasks</a>
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

    <form action="/tasks/store" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Task Title</label>
                    <input type="text" name="title" placeholder="Enter task title" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Team</label>
                    <select name="team_id" id="teamSelect" class="form-control" required>
                        <option value="">Select Team</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-style-1">
                    <label>Description</label>
                    <textarea name="description" rows="5" placeholder="Enter task description"></textarea>
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
                        <option value="to_do">To Do</option>
                        <option value="doing">Doing</option>
                        <option value="done">Done</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Priority</label>
                    <select name="priority" class="form-control" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-style-1">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control">
                </div>
            </div>
        </div>

        <div class="button-group d-flex justify-content-start flex-wrap mt-3">
            <button type="submit" class="main-btn primary-btn btn-hover">
                Create Task
            </button>
        </div>
    </form>
</div>

{{-- ✅ SAFE JSON (LIKE EDIT PAGE) --}}
<script type="application/json" id="teamsData">
    {!! json_encode($teams) !!}
</script>

@endsection

@section('scripts')
<script>
    const teams = JSON.parse(document.getElementById('teamsData').textContent);

    const teamSelect = document.getElementById('teamSelect');
    const memberSelect = document.getElementById('memberSelect');

    function loadMembers(teamId) {
        memberSelect.innerHTML = '<option value="">Select Member</option>';

        if (!teamId) return;

        const selectedTeam = teams.find(team => team.id == teamId);

        if (selectedTeam && Array.isArray(selectedTeam.users) && selectedTeam.users.length > 0) {
            selectedTeam.users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = user.name + ' (' + user.role.replace('_', ' ') + ')';
                memberSelect.appendChild(option);
            });
        }
    }

    if (teamSelect && memberSelect) {
        teamSelect.addEventListener('change', function () {
            loadMembers(this.value);
        });
    }
</script>
@endsection