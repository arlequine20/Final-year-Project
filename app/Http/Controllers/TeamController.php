<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TeamController extends Controller
{
    // Show create team form
    public function create()
    {
        return view('teams.create');
    }

    // Save team to database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect('/teams')->with('success', 'Team created successfully!');
    }
    public function index()
{
    $teams = Team::with('creator')->latest()->get();
    return view('teams.index', compact('teams'));
}
public function members($id)
{
    $team = Team::with('users')->findOrFail($id);
    $users = User::whereNotIn('id', $team->users->pluck('id'))->get();

    return view('teams.members', compact('team', 'users'));
}

public function addMember(Request $request, $id)
{
    $team = Team::findOrFail($id);

    $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $team->users()->syncWithoutDetaching([$request->user_id]);

    return back()->with('success', 'Member added successfully!');
}
public function delete($id)
{
    $team = Team::with('tasks')->findOrFail($id);

    if ($team->tasks->count() > 0) {
        return redirect('/teams')->with('error', 'Cannot delete team because it still has tasks.');
    }

    $team->delete();

    return redirect('/teams')->with('success', 'Team deleted successfully.');
}
public function edit($id)
{
    $team = Team::findOrFail($id);
    return view('teams.edit', compact('team'));
}

public function update(Request $request, $id)
{
    $team = Team::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255'
    ]);

    $team->name = $request->name;
    $team->description = $request->description;
    $team->save();

    return redirect('/teams')->with('success', 'Team updated successfully.');
}
public function removeMember($teamId, $userId)
{
    $team = \App\Models\Team::findOrFail($teamId);

    // detach user from team
    $team->users()->detach($userId);

    return back()->with('success', 'Member removed successfully.');
}
}