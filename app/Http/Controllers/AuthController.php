<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/dashboard')->with('success', 'Login successful');
            } elseif ($user->role === 'manager') {
                return redirect('/manager/dashboard')->with('success', 'Login successful');
            } else {
                return redirect('/user/dashboard')->with('success', 'Login successful');
            }
        }

        return back()->with('error', 'Invalid email or password')->withInput();
    }

    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request)
    {
        return view('reset-password', ['token' => $request->token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // ADMIN DASHBOARD
    public function dashboard()
    {
        $totalTasks = Task::count();

        $toDo = Task::where('status', 'to_do')->count();
        $doing = Task::where('status', 'doing')->count();
        $done = Task::where('status', 'done')->count();

        $highPriority = Task::where('priority', 'high')->count();

        $recentTasks = Task::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalTasks',
            'toDo',
            'doing',
            'done',
            'highPriority',
            'recentTasks'
        ));
    }

    // USER DASHBOARD
    public function managerDashboard()
    {
        $user = Auth::user();

        if ($user->role !== 'manager') {
            return redirect('/login');
        }

        $teams = $user->teams()->with('users')->get();
        $teamIds = $teams->pluck('id');

        $teamTasks = Task::whereIn('team_id', $teamIds)
            ->with(['team', 'assignee', 'creator'])
            ->latest()
            ->take(10)
            ->get();

       $managerTasks = Task::whereIn('team_id', $teamIds);
        $totalTeamTasks = (clone $managerTasks)->count();
        $toDo = (clone $managerTasks)->where('status', 'to_do')->count();
        $doing = (clone $managerTasks)->where('status', 'doing')->count();
        $done = (clone $managerTasks)->where('status', 'done')->count();

        $teamMembers = $teams->flatMap->users->unique('id');

        return view('manager.dashboard', compact(
            'teams',
            'teamTasks',
            'totalTeamTasks',
            'toDo',
            'doing',
            'done',
            'teamMembers'
        ));
    }

    public function userDashboard()
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect('/login');
        }

        // ✅ USER TASKS (assigned to user)
        $tasks = Task::where('assigned_to', $user->id);

        $totalTasks = $tasks->count();
        $toDo = (clone $tasks)->where('status', 'to_do')->count();
        $doing = (clone $tasks)->where('status', 'doing')->count();
        $done = (clone $tasks)->where('status', 'done')->count();

        $recentTasks = (clone $tasks)->latest()->take(5)->get();

        // ✅ USER TEAMS + MEMBERS
        $teams = $user->teams()->with('users')->get();

        // ✅ TEAM IDS
        $teamIds = $teams->pluck('id');

        // ✅ TEAM TASKS (NEW FEATURE 🔥)
        $teamTasks = Task::whereIn('team_id', $teamIds)
            ->with(['team', 'assignee'])
            ->latest()
            ->take(10)
            ->get();

        return view('user.dashboard', compact(
            'totalTasks',
            'toDo',
            'doing',
            'done',
            'recentTasks',
            'teams',
            'teamTasks'
        ));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully');
    }
}
