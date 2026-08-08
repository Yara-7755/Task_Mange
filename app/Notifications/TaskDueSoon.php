<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskDueSoon extends Notification implements ShouldQueue
{
    use Queueable;

    protected Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تاسك قربت تستحق: ' . $this->task->name)
            ->line('عندك تاسك اسمها "' . $this->task->name . '" موعدها بكرا.')
            ->line('التصنيف: ' . ($this->task->category->name ?? 'بدون تصنيف'))
            ->action('شوف التاسك', url('/tasks'))
            ->line('لا تنسى تخلصها بالوقت المحدد!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'due_date' => $this->task->date,
            'message' => 'التاسك "' . $this->task->name . '" موعدها بكرا',
        ];
    }
}
