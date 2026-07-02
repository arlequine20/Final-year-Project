<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCreated;
use App\Notifications\ProgressViewed;
use App\Notifications\ProgressUploaded;
use App\Notifications\TaskCompleted;
use App\Models\User;
use App\Notifications\TaskApproved;
use App\Notifications\TaskRejected;
use App\Notifications\DeadlineApproaching;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

  

class TaskController extends Controller
{
    private function getManagerTeamIds()
    {
        if (Auth::user()->role !== 'manager') {
            return collect();
        }

        return Auth::user()->teams()->pluck('teams.id');
    }

    private function canManageTask(Task $task)
    {
        if (Auth::user()->role === 'admin') {
            return true;
        }

        if (Auth::user()->role === 'manager') {
            return $task->assigned_to === Auth::id()
                || $this->getManagerTeamIds()->contains($task->team_id);
        }

        return false;
    }

    private function applyReportPeriod(Builder $query, Request $request): Builder
{
    $period = $request->query('period', 'all');

    if ($period === 'daily' && $request->filled('date')) {

        return $query->whereDate('tasks.created_at', $request->query('date'));

    }


    if ($period === 'monthly' && $request->filled('month')) {

        $parts = explode('-', $request->query('month'));

        if (count($parts) === 2) {

            return $query
                ->whereYear('tasks.created_at', $parts[0])
                ->whereMonth('tasks.created_at', $parts[1]);

        }
    }


    if ($period === 'yearly' && $request->filled('year')) {

        return $query->whereYear('tasks.created_at', $request->query('year'));

    }


    return $query;
}
    // Show all tasks
    public function index()
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    if (Auth::user()->role === 'admin') {
        $tasks = Task::where('created_by', Auth::id())
            ->with(['team', 'assignee', 'creator'])
            ->latest()
            ->get();
    } elseif (Auth::user()->role === 'manager') {
        $teamIds = $this->getManagerTeamIds();
        $tasks = Task::where(function ($query) use ($teamIds) {
                $query->whereNull('team_id')
                    ->orWhereIn('team_id', $teamIds);
            })
            ->with(['team', 'assignee', 'creator'])
            ->latest()
            ->get();
    } else {
        $tasks = Task::where('assigned_to', Auth::id())
            ->with(['team', 'assignee', 'creator'])
            ->latest()
            ->get();
    }
     foreach ($tasks as $task) {

    if ($task->due_date && $task->assigned_to) {

        $due = Carbon::parse($task->due_date);
        $today = Carbon::today();

        // If deadline is within 1 day
        if ($today->diffInDays($due, false) <= 1 && $due->isFuture()) {

            $user = User::find($task->assigned_to);

            if ($user) {

                // (important)
                $alreadyNotified = $user->notifications()
                    ->where('data->task_id', $task->id)
                    ->whereNull('read_at')
                    ->exists();

                if (!$alreadyNotified) {
                    $user->notify(new DeadlineApproaching($task));
                }
            }
        }
    }
}
    $managerTeams = Auth::user()->role === 'manager'
        ? Auth::user()->teams()->with('users')->get()
        : collect();

    return view('tasks.index', compact('tasks', 'managerTeams'));
}
    // Show create task form
// Show create task form
public function create()
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }


    $teams = Team::with('manager')->get();


    return view('tasks.create', compact('teams'));
}
    // Store task
  public function store(Request $request)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

 $request->validate([
    'title' => 'required|string|max:255',
    'description' => 'nullable|string',
    'team_id' => 'required|exists:teams,id',
    'assigned_to'=>'nullable|exists:users,id',
    'status' => 'required|in:to_do,doing,done',
    'priority' => 'required|in:low,medium,high',
    'due_date' => 'nullable|date',
    'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
]);
// HANDLE ATTACHMENT UPLOAD
$filePath = null;

if ($request->hasFile('attachment')) {

    $filePath = $request->file('attachment')
        ->store('attachments','public');

}


// CREATE TASK
$team = Team::with('creator')->findOrFail($request->team_id);


$task = Task::create([
    'title' => $request->title,
    'description' => $request->description,
    'team_id' => $request->team_id,

    // assign automatically to team manager
    'assigned_to'=>$team->manager_id,

    'created_by' => Auth::id(),
    'status' => $request->status,
    'priority' => $request->priority,
    'due_date' => $request->due_date,
    'attachment' => $filePath,
]);
    // 🔔 Notify only managers who own this team

$team = Team::with('manager')->find($task->team_id);


if($team && $team->manager){

    $team->manager->notify(
        new TaskCreated($task)
    );

}

    return redirect('/tasks')->with('success', 'Task created!');
}

    // Update task
 public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    if (Auth::user()->role !== 'admin' || $task->created_by !== Auth::id()) {
        return back()->with('error', 'Unauthorized action.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:to_do,doing,done',
        'priority' => 'required|in:low,medium,high',
        'due_date' => 'nullable|date',
        'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
    ]);

    // ✅ HANDLE FILE UPLOAD
    if ($request->hasFile('attachment')) {
        $filePath = $request->file('attachment')->store('attachments', 'public');
        $task->attachment = $filePath;
    }

    // Update task fields
    $task->update([
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status,
        'priority' => $request->priority,
        'due_date' => $request->due_date,
    ]);

    $task->save();

    // ✅ NOTIFY USER IF ASSIGNEE CHANGED
    return redirect('/tasks')->with('success', 'Task updated successfully!');
}
    // Delete task
    public function delete($id)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $task = \App\Models\Task::findOrFail($id);
    $task->delete();

    return redirect('/tasks')->with('success', 'Task deleted successfully!');
}

// Show trashed tasks
public function trash()
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $tasks = \App\Models\Task::onlyTrashed()->with(['team', 'assignee', 'creator'])->get();

    return view('tasks.trash', compact('tasks'));
}

// Restore task
public function restore($id)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $task = \App\Models\Task::onlyTrashed()->findOrFail($id);
    $task->restore();

    return redirect('/tasks/trash')->with('success', 'Task restored successfully!');
}

// Permanent delete
public function forceDelete($id)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $task = \App\Models\Task::onlyTrashed()->findOrFail($id);
    $task->forceDelete();

    return redirect('/tasks/trash')->with('success', 'Task permanently deleted!');
}

public function reports(Request $request)
{
    $user = Auth::user();


    // ADMIN = ALL TASKS
    if($user->role === 'admin'){

        $tasks = Task::query();

    }

    // MANAGER = ONLY HIS TEAMS
    elseif($user->role === 'manager'){

        $teamIds = $user->teams()
            ->pluck('teams.id');


        $tasks = Task::whereIn(
            'team_id',
            $teamIds
        );

    }

    else{

        abort(403,'Unauthorized action.');

    }



    // APPLY DATE FILTER
    $filteredTasks = $this->applyReportPeriod($tasks, $request);



    // BASIC COUNTS

    $totalTasks = (clone $filteredTasks)->count();


    $toDo = (clone $filteredTasks)
        ->where('status','to_do')
        ->count();


    $doing = (clone $filteredTasks)
        ->where('status','doing')
        ->count();


    $done = (clone $filteredTasks)
        ->where('status','done')
        ->count();



    // PRIORITY

    $low = (clone $filteredTasks)
        ->where('priority','low')
        ->count();


    $medium = (clone $filteredTasks)
        ->where('priority','medium')
        ->count();


    $high = (clone $filteredTasks)
        ->where('priority','high')
        ->count();



    // OVERDUE

    $overdue = (clone $filteredTasks)
        ->whereDate('due_date','<',now())
        ->where('status','!=','done')
        ->count();



    // COMPLETION

    $completionRate = $totalTasks > 0
        ? round(($done/$totalTasks)*100,2)
        : 0;



    // TASKS PER TEAM

    $tasksPerTeam = (clone $filteredTasks)
        ->leftJoin('teams','tasks.team_id','=','teams.id')
        ->selectRaw(
            'COALESCE(teams.name,"Unassigned") as team,
             COUNT(tasks.id) as total'
        )
        ->groupBy('teams.name')
        ->get();



    // TASKS PER USER

    $tasksPerUser = (clone $filteredTasks)
        ->leftJoin('users','tasks.assigned_to','=','users.id')
        ->selectRaw(
            'COALESCE(users.name,"Unassigned") as user,
             COUNT(tasks.id) as total'
        )
        ->groupBy('users.name')
        ->get();



    // MONTH TREND

    $tasksPerMonth = (clone $filteredTasks)
        ->selectRaw(
            'MONTH(tasks.created_at) as month,
             COUNT(tasks.id) as total'
        )
        ->groupByRaw('MONTH(tasks.created_at)')
        ->orderBy('month')
        ->get();



    $reportFilters = [

        'period'=>$request->query('period','all'),

        'date'=>$request->query('date'),

        'month'=>$request->query('month'),

        'year'=>$request->query('year',now()->year)

    ];



    return view('reports.index',compact(

        'totalTasks',
        'toDo',
        'doing',
        'done',

        'low',
        'medium',
        'high',

        'overdue',

        'completionRate',

        'tasksPerTeam',
        'tasksPerUser',
        'tasksPerMonth',

        'reportFilters'

    ));
}

    public function show($id)
{
    $task = Task::with(['team.users', 'assignee', 'creator', 'comments.user'])
                ->findOrFail($id);

    if (Auth::user()->role === 'admin') {
        if ($task->created_by !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
    } elseif (Auth::user()->role === 'manager') {
        $teamIds = $this->getManagerTeamIds();
        if ($task->team_id && !$teamIds->contains($task->team_id)) {
            return back()->with('error', 'Unauthorized action.');
        }
    } elseif (Auth::user()->role !== 'team_member') {
        return back()->with('error', 'Unauthorized action.');
    } else {
        if ($task->assigned_to !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
    }

    // ✅ Notify user when admin views progress
    if (Auth::user()->role === 'admin' && $task->assigned_to) {
        $user = User::find($task->assigned_to);
        if ($user && $task->progress_file) {
            $user->notify(new ProgressViewed($task));
        }
    }

    return view('tasks.show', compact('task'));
}
public function updateStatus($id)
{
    $task = Task::findOrFail($id);

    if ($task->assigned_to != Auth::id()) {
        return back()->with('error', 'Unauthorized action.');
    }

    if ($task->status == 'to_do') {
        $task->status = 'doing';

    } elseif ($task->status == 'doing') {
        $task->status = 'done';

        // 🔔 Notify ADMINS when DONE
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new TaskCompleted($task));
        }

    } elseif ($task->status == 'done') {
        // Reopen
        $task->status = 'to_do';
    }

    $task->save();

    return back()->with('success', 'Task updated!');
}
public function uploadProgress(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $request->validate([
        'progress_file' => 'nullable|file|max:2048',
        'progress_note' => 'nullable|string|max:1000'
    ]);

    // Restrict to assigned user
    if ($task->assigned_to != Auth::id()) {
        return back()->with('error', 'You are not assigned to this task.');
    }

    // Upload file
    if ($request->hasFile('progress_file')) {
        $filePath = $request->file('progress_file')->store('progress_files', 'public');
        $task->progress_file = $filePath;
    }

    $task->progress_note = $request->progress_note;
    $task->save();

    // 🔔 Notify ALL ADMINS
    $admins = User::where('role', 'admin')->get();

    foreach ($admins as $admin) {
        $admin->notify(new ProgressUploaded($task));
    }

    return back()->with('success', 'Progress updated!');
}

public function deleteAttachment($id)
{
    $task = Task::findOrFail($id);

    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized');
    }

    $task->attachment = null;
    $task->save();

    return back()->with('success', 'Attachment removed');
}
 
public function deleteProgress($id)
{
    $task = Task::findOrFail($id);

    if ($task->assigned_to != Auth::id() && Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized');
    }

    $task->progress_file = null; 
    $task->progress_note = null;
    $task->save();

    return back()->with('success', 'Progress removed');
}

public function approve($id)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $task = Task::findOrFail($id);

    $task->approval_status = 'approved';
    $task->save();

    if ($task->assigned_to) {
        $user = User::find($task->assigned_to);
        $user->notify(new TaskApproved($task));
    }

    return back()->with('success', 'Task approved!');
}

public function reject($id)
{
    if (Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $task = Task::findOrFail($id);

    $task->approval_status = 'rejected';
    $task->save();

    if ($task->assigned_to) {
        $user = User::find($task->assigned_to);
        $user->notify(new TaskRejected($task));
    }

    return back()->with('success', 'Task rejected!');
}
// Update task assignment only (for managers)
  public function updateAssignment(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $manager = Auth::user();

    if($manager->role !== 'manager'){
        abort(403);
    }


    $request->validate([
        'team_id'=>'required|exists:teams,id',
        'assigned_to'=>'nullable|exists:users,id'
    ]);


    // Check manager owns this team

    if(!$manager->teams->contains($request->team_id)){
        abort(403,
        'You cannot assign tasks to another team.');
    }


    $team = Team::with('users')
        ->find($request->team_id);



    // Check member belongs to team

    if($request->assigned_to){

        if(!$team->users->contains($request->assigned_to)){

            abort(403,
            'Member does not belong to your team.');

        }
    }


    // Save assignment

    $task->team_id = $request->team_id;
    $task->assigned_to = $request->assigned_to;
    $task->save();



    // 🔔 SEND NOTIFICATION TO TEAM MEMBER

    if($request->assigned_to){

        $member = User::find($request->assigned_to);

        $member->notify(
            new TaskAssigned($task)
        );

    }


    return back()->with(
        'success',
        'Task assignment updated successfully.'
    );
}
    public function edit($id)
{
    $task = Task::findOrFail($id);

    if (Auth::user()->role !== 'admin' || $task->created_by !== Auth::id()) {
        return back()->with('error', 'Unauthorized action.');
    }

    return view('tasks.edit', compact('task'));
}

public function exportReport(Request $request, $format)
{

    $user = Auth::user();



    if(!in_array($format,['xls','doc'])){

        abort(404);

    }



    if($user->role === 'admin'){

        $tasksQuery = Task::query();

    }

    elseif($user->role === 'manager'){


        $teamIds = $user->teams()
            ->pluck('teams.id');


        $tasksQuery = Task::whereIn(
            'team_id',
            $teamIds
        );

    }

    else{

        abort(403);

    }



    $tasksQuery = $this->applyReportPeriod(
        $tasksQuery,
        $request
    );



    $tasks = $tasksQuery
        ->with([
            'team',
            'assignee',
            'creator'
        ])
        ->latest()
        ->get();



    $toDo = $tasks->where('status','to_do')->count();

    $doing = $tasks->where('status','doing')->count();

    $done = $tasks->where('status','done')->count();



    $low = $tasks->where('priority','low')->count();

    $medium = $tasks->where('priority','medium')->count();

    $high = $tasks->where('priority','high')->count();



    $tasksPerTeam = $tasks
        ->groupBy(function($task){

            return $task->team->name ?? 'Unassigned';

        })
        ->map(function($group,$name){

            return [
                'team'=>$name,
                'total'=>$group->count()
            ];

        });



    $tasksPerUser = $tasks
        ->groupBy(function($task){

            return $task->assignee->name ?? 'Unassigned';

        })
        ->map(function($group,$name){

            return [
                'user'=>$name,
                'total'=>$group->count()
            ];

        });



    $html = view('reports.export',compact(

        'tasks',

        'toDo',
        'doing',
        'done',

        'low',
        'medium',
        'high',

        'tasksPerTeam',
        'tasksPerUser'

    ))->render();



    $filename =
        'task-report-'.date('YmdHis').'.'.$format;



    $contentType =
        $format === 'xls'
        ? 'application/vnd.ms-excel'
        : 'application/msword';



    return response($html,200,[

        'Content-Type'=>$contentType,

        'Content-Disposition'=>
        'attachment; filename="'.$filename.'"'

    ]);

}
}
