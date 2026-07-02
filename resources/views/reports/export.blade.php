<html>
<head>
    <meta charset="UTF-8">
    <title>Task Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
        h1, h2 { margin: 0 0 10px; }
    </style>
</head>
<body>
    <h1>Task Report</h1>
    <p>Generated on {{ date('d M Y H:i:s') }}</p>

    <h2>Summary</h2>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>Total To Do</td><td>{{ $toDo }}</td></tr>
        <tr><td>Total Doing</td><td>{{ $doing }}</td></tr>
        <tr><td>Total Done</td><td>{{ $done }}</td></tr>
        <tr><td>Low Priority</td><td>{{ $low }}</td></tr>
        <tr><td>Medium Priority</td><td>{{ $medium }}</td></tr>
        <tr><td>High Priority</td><td>{{ $high }}</td></tr>
    </table>

    <h2>Tasks Per Team</h2>
    <table>
        <tr><th>Team</th><th>Total Tasks</th></tr>
        @foreach($tasksPerTeam as $team)
            <tr><td>{{ $team->team }}</td><td>{{ $team->total }}</td></tr>
        @endforeach
    </table>

    <h2>Tasks Per User</h2>
    <table>
        <tr><th>User</th><th>Total Tasks</th></tr>
        @foreach($tasksPerUser as $user)
            <tr><td>{{ $user->user }}</td><td>{{ $user->total }}</td></tr>
        @endforeach
    </table>

    <h2>All Tasks</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Team</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Created By</th>
            <th>Due Date</th>
        </tr>
        @foreach($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->team->name ?? 'N/A' }}</td>
                <td>{{ $task->assignee->name ?? 'Unassigned' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</td>
                <td>{{ ucfirst($task->priority) }}</td>
                <td>{{ $task->creator->name ?? 'Unknown' }}</td>
                <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>