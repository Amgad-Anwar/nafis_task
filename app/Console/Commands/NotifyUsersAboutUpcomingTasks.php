<?php

namespace App\Console\Commands;

use App\Mail\TaskDueNotificationMail;
use App\Models\Task;
use App\Notifications\TaskDueNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyUsersAboutUpcomingTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:notify-upcoming';
    protected $description = 'Notify users about tasks due in the next 24 hours';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $tasks = Task::where('status','!=' , 'completed')
                    ->where('due_date', '>', $now)
                     ->where('due_date', '<=', $now->addDay())
                     ->get();

        foreach ($tasks as $task) {
            foreach ($task->users as $user) {

                $pivot = $task->users()->where('user_id', $user->id)->first()->pivot;

                if (!$pivot->notified_at) {

                    Mail::to($user->email)
                    ->send(new TaskDueNotificationMail($task));

                    $task->users()->updateExistingPivot($user->id, [
                        'notified_at' => Carbon::now(),
                    ]);
                }
            }
        }

        $this->info('Users notified about tasks due in the next 24 hours.');

    }
}
