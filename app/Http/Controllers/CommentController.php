<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment; // ✅ IMPORT MODEL
use Illuminate\Support\Facades\Auth; // ✅ IMPORT AUTH
use App\Models\Task;
use App\Models\User;
use App\Notifications\NewComment;

class CommentController extends Controller
{
    // Store a new comment
    public function store(Request $request, $taskId)
{
    $request->validate([
        'content' => 'required|string|max:1000'
    ]);

    $task = Task::findOrFail($taskId);

    Comment::create([
        'content' => $request->input('content'),
        'task_id' => $taskId,
        'user_id' => Auth::id(),
    ]);

    // 🔔 Notify ADMINS
    $admins = User::where('role', 'admin')->get();

    foreach ($admins as $admin) {
        $admin->notify(new NewComment($task));
    }

    return back()->with('success', 'Comment added!');
}
    public function destroy($id)
{
    $comment = \App\Models\Comment::findOrFail($id);

    // Only owner OR admin can delete
    if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
        return back()->with('error', 'Unauthorized action.');
    }

    $comment->delete();

    return back()->with('success', 'Comment deleted!');
}  
} 