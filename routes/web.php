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
    // $user = User::find(1);
    //  $task = Task::find(1);  
    // Mail::to('amgad.anwar.dev@gmail.com')
    // ->send(new TaskDueNotificationMail($task));
    return 'Test notification sent!';
});
