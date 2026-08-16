<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class TaskService
{

    public function createTask(array $data): Task
    {
        if (!empty($data['group_id'])) {
            $group = Group::findOrFail($data['group_id']);

            if (!$group->members->contains(Auth::id())) {
                throw new \Exception('You must be a member of this group to create tasks.');
            }

            if (!empty($data['assigned_to']) && !$group->members->contains($data['assigned_to'])) {
                throw new \Exception('Assigned user must be a member of the group.');
            }
        }

        return Task::create([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'status'      => $data['status'] ?? 'pending',
            'due_date'    => $data['due_date'] ?? null,
            'user_id'     => Auth::id(), // منشئ المهمة
            'group_id'    => $data['group_id'] ?? null,
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);
    }


    public function getGroupTasks(Group $group)
    {
        if (!$group->members->contains(Auth::id())) {
            throw new \Exception('Unauthorized access to group tasks.');
        }

        return Task::where('group_id', $group->id)
            ->with(['creator', 'assignedUser'])
            ->latest()
            ->get();
    }
}
