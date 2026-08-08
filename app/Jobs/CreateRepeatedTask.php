<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateRepeatedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle(): void
    {
        Task::create([
            'name'                    => $this->task->name,
            'description'             => $this->task->description,
            'date'                    => now()->addMinutes($this->task->repeat_interval_minutes ?? 0),
            'category_id'             => $this->task->category_id,
            'completed'               => false,
            'priority'                => $this->task->priority,
            'repeat_type'             => $this->task->repeat_type,
            'repeat_interval_minutes' => $this->task->repeat_interval_minutes,
            'user_id'                 => $this->task->user_id,
        ]);

        $this->task->update(['repeated' => true]);
    }
}
