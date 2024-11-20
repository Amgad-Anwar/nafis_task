<?php

use App\Mail\TaskDueNotificationMail;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskDueNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('test', function () {
    // $user = User::find(1);  // Find a user (replace with a valid user ID)
    //  $task = Task::find(1);  // Find a task (replace with a valid task ID)
    // // $user->notify(new TaskDueNotification($task)  );

    $now = Carbon::now();
    $tasks = Task::where('status','!=' , 'completed')
                ->where('due_date', '>', $now->format('Y-m-d H:i:s'))
                 ->where('due_date', '<=', $now->addDay()->format('Y-m-d H:i:s'))
                 ->get();

                 dd( $tasks  , $now->format('Y-m-d H:i:s') , $now->addDay()->format('Y-m-d H:i:s')) ;

    // Mail::to('amgad.anwar.dev@gmail.com')
    // ->send(new TaskDueNotificationMail($task));
    return 'Test notification sent!';
});
