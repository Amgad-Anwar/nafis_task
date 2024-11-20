<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaginationResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserTaskController extends Controller
{



    public function userTasks(Request $request)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,completed,overdue',
        ]);

        $tasks = auth()->user()->tasks()->when($request->status, function($q) use ($request) {
            $q->where('status', $request->status);
        })->paginate();

        //==== we can use it like : =======
        // $tasks = DB::table('tasks')
        //     ->join('task_user', 'tasks.id', '=', 'task_user.task_id') // Join the pivot table
        //     ->where('task_user.user_id', auth()->id()) // Filter by the authenticated user's ID
        //     ->when($request->status, function ($query) use ($request) {
        //         $query->where('tasks.status', $request->status);
        //     })
        //     ->select('tasks.*')
        //     ->paginate();

        return response()->json([
            'success' => true,
            'result' => new PaginationResource( $tasks, TaskResource::class)
        ]);
    }




}
