<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDueSoon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-task-due-soon-notifications')]
#[Description('يرسل إشعار لكل يوزر عن التاسكات يلي موعدها بكرا')]
class SendTaskDueSoonNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = now()->addDay()->toDateString();

        $tasks = Task::where('completed', false)
            ->whereDate('date', $tomorrow)
            ->get();

        foreach ($tasks as $task) {
            $task->user->notify(new TaskDueSoon($task));
        }

        $this->info($tasks->count() . ' إشعار تم إرساله.');
    }
}
