<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // Show all tasks
    public function index()
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role === 'admin') {
        $tasks = Task::with(['team', 'assignee', 'creator'])->latest()->get();
    } else {
        $tasks = Task::with(['team', 'assignee', 'creator'])
            ->where('assigned_to', Auth::id())
            ->latest()
            ->get();
    }

    return view('tasks.index', compact('tasks'));
}
    // Show create task form
  public function create()
{
    $teams = \App\Models\Team::with('users')->get(); // ✅ LOAD USERS

    return view('tasks.create', compact('teams'));
}

    // Store task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'required|exists:teams,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:to_do,doing,done',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'team_id' => $request->team_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        return redirect('/tasks')->with('success', 'Task created successfully!');
    }

    // Show edit form
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $teams = Team::with('users')->get();

        return view('tasks.edit', compact('task', 'teams'));
    }

    // Update task
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_id' => 'required|exists:teams,id',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:to_do,doing,done',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'team_id' => $request->team_id,
            'assigned_to' => $request->assigned_to,
            'status' => $request->status,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        return redirect('/tasks')->with('success', 'Task updated successfully!');
    }

    // Delete task
    public function delete($id)
{
    $task = \App\Models\Task::findOrFail($id);
    $task->delete();

    return redirect('/tasks')->with('success', 'Task deleted successfully!');
}

// Show trashed tasks
public function trash()
{
    $tasks = \App\Models\Task::onlyTrashed()->with(['team', 'assignee', 'creator'])->get();

    return view('tasks.trash', compact('tasks'));
}

// Restore task
public function restore($id)
{
    $task = \App\Models\Task::onlyTrashed()->findOrFail($id);
    $task->restore();

    return redirect('/tasks/trash')->with('success', 'Task restored successfully!');
}

// Permanent delete
public function forceDelete($id)
{
    $task = \App\Models\Task::onlyTrashed()->findOrFail($id);
    $task->forceDelete();

    return redirect('/tasks/trash')->with('success', 'Task permanently deleted!');
}

public function reports()
{
    // STATUS
    $toDo = \App\Models\Task::where('status', 'to_do')->count();
    $doing = \App\Models\Task::where('status', 'doing')->count();
    $done = \App\Models\Task::where('status', 'done')->count();

    // PRIORITY
    $low = \App\Models\Task::where('priority', 'low')->count();
    $medium = \App\Models\Task::where('priority', 'medium')->count();
    $high = \App\Models\Task::where('priority', 'high')->count();

    // TASKS PER TEAM
    $tasksPerTeam = \App\Models\Task::selectRaw('teams.name as team, COUNT(tasks.id) as total')
        ->join('teams', 'tasks.team_id', '=', 'teams.id')
        ->groupBy('teams.name')
        ->get();

    // TASKS PER USER (ASSIGNEE)
    $tasksPerUser = \App\Models\Task::selectRaw('users.name as user, COUNT(tasks.id) as total')
        ->join('users', 'tasks.assigned_to', '=', 'users.id')
        ->groupBy('users.name')
        ->get();

    // TASKS PER MONTH
    $tasksPerMonth = \App\Models\Task::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return view('reports.index', compact(
        'toDo', 'doing', 'done',
        'low', 'medium', 'high',
        'tasksPerTeam',
        'tasksPerUser',
        'tasksPerMonth'
    ));
}

public function updateStatus($id)
{
    $task = \App\Models\Task::findOrFail($id);

    // Security: only assigned user can update
    if ($task->assigned_to != Auth::id()) {
        return back()->with('error', 'Unauthorized action.');
    }

    // Move status forward
    if ($task->status == 'to_do') {
        $task->status = 'doing';
    } elseif ($task->status == 'doing') {
        $task->status = 'done';
    }

    $task->save();

    return back()->with('success', 'Task updated!');
}
}