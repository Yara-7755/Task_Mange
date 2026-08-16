<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;
use App\Models\GroupInvitation;


class InvitationController extends Controller
{
    protected GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function sendInvitation(Request $request, Group $group)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $this->groupService->inviteMember($group, $request->email);
            return back()->with('success', 'Invitation sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function acceptInvitation($token)
    {
        try {
            $group = $this->groupService->acceptInvitation($token);
            return redirect()->route('groups.show', $group->id)->with('success', 'You have joined the group!');
        } catch (\Exception $e) {
            return redirect()->route('groups.index')->with('error', $e->getMessage());
        }


    }

    public function accept(string $token)
    {
        $group = $this->groupService->acceptInvitation($token);

        return redirect()->route('groups.show', $group->id)
            ->with('success', 'You joined ' . $group->name . '!');
    }
    public function show(string $token)
    {
        $invitation = GroupInvitation::with('group')
            ->where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        return view('invitations.confirm', compact('invitation'));
    }

    public function decline(string $token)
    {
        $this->groupService->declineInvitation($token);

        return redirect()->route('groups.index')
            ->with('success', 'Invitation declined.');
    }

    public function send(Request $request, Group $group)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $this->groupService->inviteMember($group, $request->email);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Invitation sent to ' . $request->email . '.');
    }
}
