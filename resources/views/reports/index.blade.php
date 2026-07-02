@extends('layout')

@section('title', 'Reports')

@section('content')

@php
    $reportFilters = $reportFilters ?? [
        'period' => request('period', 'all'),
        'date' => request('date'),
        'month' => request('month'),
        'year' => request('year', now()->year),
    ];
    $exportQuery = http_build_query(array_filter($reportFilters, fn ($value) => filled($value)));
@endphp

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Reports & Analytics</h3>
        <p class="text-muted mb-0">System insights and performance overview</p>
    </div>

    <div class="d-flex gap-2">
        <a href="/dashboard" class="main-btn light-btn btn-hover">
            Back to Dashboard
        </a>

        @if(auth()->user()->role === 'admin')
            <a href="/reports/export/xls{{ $exportQuery ? '?' . $exportQuery : '' }}" class="main-btn primary-btn btn-hover">
                Download Excel
            </a>
            <a href="/reports/export/doc{{ $exportQuery ? '?' . $exportQuery : '' }}" class="main-btn secondary-btn btn-hover">
                Download Word
            </a>
        @endif
    </div>
</div>

@if(auth()->user()->role === 'admin')
    <div class="page-card">
        <form method="GET" action="/reports" class="row g-3 align-items-end">
            <div class="col-lg-3 col-md-6">
                <label class="form-label">Report Period</label>
                <select name="period" id="reportPeriod" class="form-control">
                    <option value="all" {{ $reportFilters['period'] === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="daily" {{ $reportFilters['period'] === 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="monthly" {{ $reportFilters['period'] === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $reportFilters['period'] === 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-6 report-period-field" data-period-field="daily">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $reportFilters['date'] }}">
            </div>

            <div class="col-lg-3 col-md-6 report-period-field" data-period-field="monthly">
                <label class="form-label">Month</label>
                <input type="month" name="month" class="form-control" value="{{ $reportFilters['month'] }}">
            </div>

            <div class="col-lg-3 col-md-6 report-period-field" data-period-field="yearly">
                <label class="form-label">Year</label>
                <input type="number" name="year" class="form-control" min="2000" max="2100" value="{{ $reportFilters['year'] }}">
            </div>

            <div class="col-lg-3 col-md-6 d-flex gap-2">
                <button type="submit" class="main-btn primary-btn btn-hover">Generate</button>
                <a href="/reports" class="main-btn light-btn btn-hover">Reset</a>
            </div>
        </form>
    </div>
@endif




<div class="row mt-4">

    {{-- STATUS SUMMARY --}}
    <div class="col-lg-4 mb-3">
        <div class="page-card">
            <h5>Status Overview</h5>

            <div class="d-flex justify-content-between mt-3">
                <span>To Do</span>
                <strong>{{ $toDo }}</strong>
            </div>

            <div class="progress mb-3">
                <div class="progress-bar bg-secondary"
                     style="width: {{ $totalTasks > 0 ? ($toDo/$totalTasks)*100 : 0 }}%">
                </div>
            </div>


            <div class="d-flex justify-content-between">
                <span>Doing</span>
                <strong>{{ $doing }}</strong>
            </div>

            <div class="progress mb-3">
                <div class="progress-bar bg-warning"
                     style="width: {{ $totalTasks > 0 ? ($doing/$totalTasks)*100 : 0 }}%">
                </div>
            </div>


            <div class="d-flex justify-content-between">
                <span>Completed</span>
                <strong>{{ $done }}</strong>
            </div>

            <div class="progress">
                <div class="progress-bar bg-success"
                     style="width: {{ $completionRate }}%">
                </div>
            </div>

        </div>
    </div>



    {{-- PRIORITY --}}
    <div class="col-lg-4 mb-3">

        <div class="page-card">

            <h5>Priority Overview</h5>

            <ul class="list-group mt-3">

                <li class="list-group-item d-flex justify-content-between">
                    Low
                    <span>{{ $low }}</span>
                </li>


                <li class="list-group-item d-flex justify-content-between">
                    Medium
                    <span>{{ $medium }}</span>
                </li>


                <li class="list-group-item d-flex justify-content-between">
                    High
                    <span>{{ $high }}</span>
                </li>


            </ul>

        </div>

    </div>




    {{-- QUICK INSIGHT --}}
    <div class="col-lg-4 mb-3">

        <div class="page-card text-center">

            <h5>Performance</h5>

            <h1 class="mt-4">
                {{ $completionRate }}%
            </h1>

            <p>
                Overall Completion
            </p>


            @if($overdue > 0)

            <div class="alert alert-danger">
                {{ $overdue }} overdue task(s)
            </div>

            @else

            <div class="alert alert-success">
                No overdue tasks
            </div>

            @endif


        </div>

    </div>

</div>
<div class="row mt-3">

<div class="col-lg-12">

<div class="page-card">

<h5>Task Distribution By Team</h5>


<table class="table table-dark mt-3">

<thead>

<tr>
<th>Team</th>
<th>Total Tasks</th>
</tr>

</thead>


<tbody>

@foreach($tasksPerTeam as $team)

<tr>

<td>
{{ $team->team }}
</td>

<td>
{{ $team->total }}
</td>

</tr>

@endforeach


</tbody>

</table>


</div>

</div>

</div>

<div class="row mt-3">

    <!-- STATUS CHART -->
    <div class="col-lg-6 mb-3">
        <div class="page-card">
            <h5>Tasks by Status</h5>
            <canvas id="statusChart"></canvas>
        </div>
    </div>


    <!-- PRIORITY CHART -->
    <div class="col-lg-6 mb-3">
        <div class="page-card">
            <h5>Tasks by Priority</h5>
            <canvas id="priorityChart"></canvas>
        </div>
    </div>


    <!-- TEAM CHART -->
    <div class="col-lg-6 mb-3">
        <div class="page-card">
            <h5>Tasks per Team</h5>
            <canvas id="teamChart"></canvas>
        </div>
    </div>


    <!-- USER CHART -->
    <div class="col-lg-6 mb-3">
        <div class="page-card">
            <h5>Tasks per User</h5>
            <canvas id="userChart"></canvas>
        </div>
    </div>


    <!-- MONTH TREND -->
    <div class="col-lg-12 mb-3">
        <div class="page-card">
            <h5>Task Creation Trend</h5>
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
const reportPeriod = document.getElementById('reportPeriod');
const periodFields = document.querySelectorAll('.report-period-field');

function toggleReportFields() {
    if (!reportPeriod) return;

    periodFields.forEach(field => {
        field.style.display = field.dataset.periodField === reportPeriod.value ? '' : 'none';
    });
}

if (reportPeriod) {
    reportPeriod.addEventListener('change', toggleReportFields);
    toggleReportFields();
}

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
