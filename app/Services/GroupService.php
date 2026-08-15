<?php

namespace App\Services;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupInvitation;
use App\Mail\GroupInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class GroupService
{

    public function getUserGroups()
    {
        return Auth::user()->groups;
    }


    public function createGroup(array $data): Group
    {
        $group = Group::create([
            'name' => $data['name'],
            'created_by' => Auth::id(),
        ]);

        $group->members()->attach(Auth::id(), [
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        return $group;
    }


    public function removeMember(Group $group, int $userId): bool
    {
        $currentUserRole = $group->members()->where('user_id', Auth::id())->first()->pivot->role ?? null;

        if ($currentUserRole !== 'admin') {
            throw new \Exception('Only admins can remove members.');
        }

        if ($userId === Auth::id()) {
            throw new \Exception('You cannot remove yourself from the group.');
        }

        $group->members()->detach($userId);

        return true;
    }
    public function inviteMember(Group $group, string $email): GroupInvitation
    {
        $currentUserRole = $group->members()->where('user_id', Auth::id())->first()->pivot->role ?? null;
        if ($currentUserRole !== 'admin') {
            throw new \Exception('Only group admins can send invitations.');
        }

        if ($group->members()->where('email', $email)->exists()) {
            throw new \Exception('This user is already a member of the group.');
        }

        $invitation = GroupInvitation::updateOrCreate(
            ['group_id' => $group->id, 'email' => $email],
            [
                'token' => Str::random(32),
                'status' => 'pending',
            ]
        );

        Mail::to($email)->send(new GroupInvitationMail($invitation));

        return $invitation;
    }

    public function acceptInvitation(string $token)
    {
        $invitation = GroupInvitation::where('token', $token)->where('status', 'pending')->firstOrFail();

        if (Auth::user()->email !== $invitation->email) {
            throw new \Exception('This invitation is for a different email address.');
        }

        $invitation->group->members()->attach(Auth::id(), [
            'role' => 'member',
            'joined_at' => now(),
        ]);


        $invitation->update(['status' => 'accepted']);

        return $invitation->group;
    }
}
