@extends('layout')

@section('title', 'Reports')

@section('content')

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Reports & Analytics</h3>
        <p class="text-muted mb-0">System insights and performance overview</p>
    </div>

    <a href="/dashboard" class="main-btn light-btn btn-hover">
        Back to Dashboard
    </a>
</div>

<div class="row mt-3">

    <!-- STATUS -->
    <div class="col-lg-6">
        <div class="page-card">
            <h5>Tasks by Status</h5>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- PRIORITY -->
    <div class="col-lg-6">
        <div class="page-card">
            <h5>Tasks by Priority</h5>
            <canvas id="priorityChart"></canvas>
        </div>
    </div>

    <!-- TEAM -->
    <div class="col-lg-6 mt-3">
        <div class="page-card">
            <h5>Tasks per Team</h5>
            <canvas id="teamChart"></canvas>
        </div>
    </div>

    <!-- USER -->
    <div class="col-lg-6 mt-3">
        <div class="page-card">
            <h5>Tasks per User</h5>
            <canvas id="userChart"></canvas>
        </div>
    </div>

    <!-- MONTH -->
    <div class="col-lg-12 mt-3">
        <div class="page-card">
            <h5>Tasks per Month</h5>
            <canvas id="monthChart"></canvas>
        </div>
    </div>

</div>

@endsection

{{-- PASS DATA --}}
<script type="application/json" id="reportData">
{!! json_encode([
    'status' => [$toDo, $doing, $done],
    'priority' => [$low, $medium, $high],
    'teams' => $tasksPerTeam,
    'users' => $tasksPerUser,
    'months' => $tasksPerMonth
]) !!}
</script>

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const data = JSON.parse(document.getElementById('reportData').textContent);

// STATUS
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['To Do', 'Doing', 'Done'],
        datasets: [{
            data: data.status
        }]
    }
});

// PRIORITY
new Chart(document.getElementById('priorityChart'), {
    type: 'bar',
    data: {
        labels: ['Low', 'Medium', 'High'],
        datasets: [{
            label: 'Tasks',
            data: data.priority
        }]
    }
});

// TEAM
new Chart(document.getElementById('teamChart'), {
    type: 'bar',
    data: {
        labels: data.teams.map(t => t.team),
        datasets: [{
            label: 'Tasks',
            data: data.teams.map(t => t.total)
        }]
    }
});

// USER
new Chart(document.getElementById('userChart'), {
    type: 'bar',
    data: {
        labels: data.users.map(u => u.user),
        datasets: [{
            label: 'Tasks',
            data: data.users.map(u => u.total)
        }]
    }
});

// MONTH
new Chart(document.getElementById('monthChart'), {
    type: 'line',
    data: {
        labels: data.months.map(m => 'Month ' + m.month),
        datasets: [{
            label: 'Tasks Created',
            data: data.months.map(m => m.total)
        }]
    }
});
</script>

@endsection