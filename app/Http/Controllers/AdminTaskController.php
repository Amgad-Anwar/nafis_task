<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginationResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTaskController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
        ]);

        $task = Task::create($validated);

        // Assign users if specified


        return response()->json([
            'success'=> true ,
            'message' => 'Task created successfully',
            'task' => new TaskResource($task),
        ]) ;
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'due_date' => 'date',
            'status' => 'in:pending,completed,overdue',
        ]);
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        $task->update($validated);

        return response()->json([
            'success'=> true ,
            'message' => 'Task updated successfully',
            'task' => new TaskResource($task),
        ]) ;
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }

    public function index( Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,completed,overdue',
        ]);

        $tasks = Task::when($request->status, function($q) use ($request) {
            $q->where('status', $request->status);
        })
        ->paginate();

        return response()->json([
            'success' => true,
            'result' => new PaginationResource( $tasks, TaskResource::class)
        ]);
    }


    public function assignUsers(Request $request, $id)
    {
        $validated = $request->validate([
            'user_emails' => 'required|array',
            'user_emails.*' => 'exists:users,email',
        ]);

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.',
            ], 404);
        }

       $users = User::whereIn('email', $request->user_emails)->get();

        $assignedUserIds = $task->users->pluck('id')->toArray();

        $newUsers = $users->filter(function ($user) use ($assignedUserIds) {
            return !in_array($user->id, $assignedUserIds);
        });

        if ($newUsers->isNotEmpty()) {
            $task->users()->attach($newUsers->pluck('id'));
        }

        return response()->json([
            'success' => true,
            'message' => $newUsers->isNotEmpty() ? 'Users assigned successfully.' : 'No new users to assign.',
        ]);
    }


}
