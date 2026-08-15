<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    protected GroupService $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index()
    {
        $groups = $this->groupService->getUserGroups();
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $this->groupService->createGroup($request->only('name'));

        return redirect()->route('groups.index')->with('success', 'Group created successfully!');
    }

    public function show(Group $group)
    {
        if (!$group->members->contains(auth()->id())) {
            abort(403, 'Unauthorized access.');
        }

        $group->load('members', 'invitations');
        return view('groups.show', compact('group'));
    }

    public function removeMember(Group $group, $userId)
    {
        try {
            $this->groupService->removeMember($group, (int)$userId);
            return back()->with('success', 'Member removed successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
