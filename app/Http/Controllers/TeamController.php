<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TeamController extends Controller
{
    private function ensureAdmin()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
    }

    // Show create team form
    public function create()
{
    $this->ensureAdmin();

    $managers = User::where('role','manager')->get();

    return view('teams.create', compact('managers'));
}

    // Save team to database
    public function store(Request $request)
    {
        $this->ensureAdmin();

       $request->validate([

'name'=>'required|string|max:255',

'description'=>'nullable|string',

'manager_id'=>'required|exists:users,id'

]);

  Team::create([

    'name'=>$request->name,

    'description'=>$request->description,

    'created_by'=>Auth::id(),

    'manager_id'=>$request->manager_id,

]);

        return redirect('/teams')->with('success', 'Team created successfully!');
    }
public function index()
{
    $user = Auth::user();

    if ($user->role === 'manager') {

        $teams = $user->teams()
            ->with(['creator','users'])
            ->latest()
            ->get();

    } elseif ($user->role === 'admin') {

        $teams = Team::with(['creator','users'])
            ->latest()
            ->get();

    } else {

        abort(403,'Unauthorized action.');

    }


    return view('teams.index', compact('teams'));
}
public function members($id)
{
    $team = Team::with(['users', 'manager'])->findOrFail($id);

    $user = Auth::user();


    if($user->role === 'manager'){

        if(!$user->teams->contains($team->id)){
            abort(403,'Unauthorized action.');
        }

    } 
    elseif($user->role !== 'admin'){

        abort(403,'Unauthorized action.');

    }


    $users = collect();


    if($user->role === 'admin'){
        $excludedIds = $team->users->pluck('id');
        if ($team->manager_id) {
            $excludedIds->push($team->manager_id);
        }

        $users = User::whereNotIn('id', $excludedIds->filter())->get();
    }


    return view('teams.members', compact(
        'team',
        'users'
    ));
}

public function addMember(Request $request, $id)
{
    $this->ensureAdmin();

    $team = Team::findOrFail($id);

    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $team->users()->syncWithoutDetaching([$request->user_id]);

    return back()->with('success', 'Member added successfully!');
}
public function delete($id)
{
    $this->ensureAdmin();

    $team = Team::with('tasks')->findOrFail($id);

    if ($team->tasks->count() > 0) {
        return redirect('/teams')->with('error', 'Cannot delete team because it still has tasks.');
    }

    $team->delete();

    return redirect('/teams')->with('success', 'Team deleted successfully.');
}
public function edit($id)
{
    $this->ensureAdmin();

    $team = Team::findOrFail($id);

    $managers = User::where('role','manager')->get();

    return view('teams.edit', compact('team','managers'));
}

public function update(Request $request, $id)
{
    $this->ensureAdmin();

    $team = Team::findOrFail($id);


    $request->validate([

        'name' => 'required|string|max:255',

        'description' => 'nullable|string',

        'manager_id' => 'required|exists:users,id'

    ]);


    $team->update([

        'name'=>$request->name,

        'description'=>$request->description,

        'manager_id'=>$request->manager_id

    ]);


    return redirect('/teams')
        ->with('success','Team updated successfully.');
}
public function removeMember($teamId, $userId)
{
    $this->ensureAdmin();

    $team = \App\Models\Team::findOrFail($teamId);

    // detach user from team
    $team->users()->detach($userId);

    return back()->with('success', 'Member removed successfully.');
}
}
