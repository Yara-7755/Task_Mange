<?php
namespace App\Console\Commands;

use App\Models\Task;
use App\Jobs\CreateRepeatedTask;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Attributes\Description;

#[Signature('app:process-repeating-tasks')]
#[Description('Process tasks that need to be repeated')]
class ProcessRepeatingTasks extends Command
{
    public function handle()
    {
        $tasks = Task::where('repeated', false)
            ->where('repeat_type', '!=', 'none')
            ->where(function ($query) {
                $query->where('completed', true)
                    ->orWhere(function ($q) {
                        $q->where('completed', false)
                            ->whereNotNull('date')
                            ->where('date', '<=', now());
                    });
            })
            ->get();

        $count = 0;

        foreach ($tasks as $task) {
            $intervalMinutes = match ($task->repeat_type) {
                'daily'  => 24 * 60,
                'weekly' => 7 * 24 * 60,
                'custom' => $task->repeat_interval_minutes,
                default  => null,
            };

            if (! $intervalMinutes) {
                continue;
            }

            $baseTime = $task->completed_at ?? \Carbon\Carbon::parse($task->date);
            $dueAt = $baseTime->addMinutes($intervalMinutes);

            if (now()->greaterThanOrEqualTo($dueAt)) {
                CreateRepeatedTask::dispatch($task);
                $count++;
            }
        }

        $this->info($count . ' Repeated tasks processed.');
    }
}
