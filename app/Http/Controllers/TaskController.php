<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskController extends Controller
{
    //

    public function assignTaskPage()
{
    $users = User::where('id', '!=', auth('auth')->id())->get();
    $channels = Channel::all();

    return view('assign', compact('users', 'channels'));
}

    public function saveupdateTask(Request $request, $id = null)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'channel_id' => 'nullable|numeric',
        'user_id' => 'nullable|numeric',
        'assigned_user_id' => 'nullable|numeric',
        'status' => 'nullable|string',
        'priority' => 'nullable|string',
        'deadline_date' => 'nullable|date',
    ]);

    $task = $id ? Task::findOrFail($id) : new Task();

    $task->title = $request->title;
    $task->description = $request->description;
    $task->channel_id = $request->channel_id;
    $task->user_id = $request->user_id ?? auth('auth')->id();
    $task->assigned_user_id = $request->assigned_user_id;
    $task->status = $request->status ?? 'Pending';
    $task->priority = $request->priority ?? 'Medium';
    $task->deadline_date = $request->deadline_date;

    $task->save();

    // dd($request->all());

    return redirect('/home')->with('success', $id ? 'Task updated!' : 'Task created!');
}

public function updateStatus(Request $request, $id)
{
    $task = Task::findOrFail($id);
    $task->status = $request->input('status');
    $task->save();

    return redirect()->back()->with('success', 'Task status updated.');
}




public function showTaskList()
{
    $userId = Auth::id();

    $assignedToMe = Task::with('user')
        ->where('assigned_user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    // $assignedByMe = Task::with('assignedUser')
    //     ->where('user_id', $userId)
    //     ->orderBy('created_at', 'desc')
    //     ->get();

    return view('task-list', compact('assignedToMe'));
}

public function myshowTaskList()
{
    $userId = Auth::id();

    // $assignedToMe = Task::with('user')
    //     ->where('assigned_user_id', $userId)
    //     ->orderBy('created_at', 'desc')
    //     ->get();

    $assignedByMe = Task::with('assignedUser')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('my_task', compact('assignedByMe'));
}

}
