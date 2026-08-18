<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function createTask(array $data): Task
    {
        /*
        |--------------------------------------------------------------------------
        | Group validation
        |--------------------------------------------------------------------------
        */

        if (!empty($data['group_id'])) {

            $group = Group::findOrFail(
                $data['group_id']
            );

            // Make sure current user is a member of the group
            if (!$group->members->contains(Auth::id())) {
                throw new \Exception(
                    'You must be a member of this group to create tasks.'
                );
            }

            // If task is assigned to another user,
            // make sure that user belongs to the group
            if (
                !empty($data['assigned_to']) &&
                !$group->members->contains(
                    $data['assigned_to']
                )
            ) {
                throw new \Exception(
                    'Assigned user must be a member of the group.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate custom repeat interval
        |--------------------------------------------------------------------------
        */

        $repeatMinutes = null;

        if (
            ($data['repeat_type'] ?? 'none') === 'custom' &&
            !empty($data['repeat_interval_value'])
        ) {
            $repeatMinutes =
                ($data['repeat_interval_unit'] ?? 'minutes') === 'hours'
                    ? $data['repeat_interval_value'] * 60
                    : $data['repeat_interval_value'];
        }

        /*
        |--------------------------------------------------------------------------
        | Create Task
        |--------------------------------------------------------------------------
        */

        return Task::create([
            'name'                    => $data['name'],
            'description'             => $data['description'] ?? null,
            'date'                    => $data['date'] ?? null,
            'category_id'             => $data['category_id'],
            'priority'                => $data['priority'],
            'completed'               => !empty($data['completed']),
            'repeat_type'             => $data['repeat_type'] ?? 'none',
            'repeat_interval_minutes' => $repeatMinutes,

            // Current logged-in user
            'user_id'                 => Auth::id(),

            // Group fields
            'group_id'                => $data['group_id'] ?? null,
            'assigned_to'             => $data['assigned_to'] ?? null,
        ]);
    }

    public function getGroupTasks(Group $group)
    {
        if (!$group->members->contains(Auth::id())) {
            throw new \Exception(
                'Unauthorized access to group tasks.'
            );
        }

        return Task::where(
            'group_id',
            $group->id
        )
            ->with([
                'creator',
                'assignedUser'
            ])
            ->latest()
            ->get();
    }
}
