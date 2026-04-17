<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Task;
use App\Models\Team;

class AuthController extends Controller
{
    // Show register page
    public function showRegister()
    {
        return view('register');
    }

    // Register user
   public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'role' => 'required|in:admin,manager,team_member',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'role' => $request->role,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    return redirect('/login')->with('success', 'Account created successfully');
}

    // Show login page
    public function showLogin()
    {
        return view('login');
    }

    // Login user
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // ✅ GET LOGGED-IN USER
        $user = Auth::user();

        // ✅ REDIRECT BASED ON ROLE
        if ($user->role === 'admin') {
            return redirect('/dashboard')->with('success', 'Login successful');
        } else {
            return redirect('/user/dashboard')->with('success', 'Login successful');
        }
    }

    return back()->with('error', 'Invalid email or password')->withInput();
}

    // Dashboard

   public function dashboard()
{
    $totalTasks = \App\Models\Task::count();

    $toDo = \App\Models\Task::where('status', 'to_do')->count();
    $doing = \App\Models\Task::where('status', 'doing')->count();
    $done = \App\Models\Task::where('status', 'done')->count();

    $highPriority = \App\Models\Task::where('priority', 'high')->count();

    // Latest 5 tasks
    $recentTasks = \App\Models\Task::latest()->take(5)->get();

    return view('dashboard', compact(
        'totalTasks',
        'toDo',
        'doing',
        'done',
        'highPriority',
        'recentTasks'
    ));
}
public function userDashboard()
{
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    // Get only tasks assigned to this user
    $tasks = Task::where('assigned_to', $user->id);

    // Stats
    $totalTasks = $tasks->count();
    $toDo = (clone $tasks)->where('status', 'to_do')->count();
    $doing = (clone $tasks)->where('status', 'doing')->count();
    $done = (clone $tasks)->where('status', 'done')->count();

    // ✅ ADD THIS (recent tasks)
    $recentTasks = (clone $tasks)->latest()->take(5)->get();

    return view('user.dashboard', compact(
        'totalTasks',
        'toDo',
        'doing',
        'done',
        'recentTasks'
    ));
}
public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login')->with('success', 'Logged out successfully');
}
}