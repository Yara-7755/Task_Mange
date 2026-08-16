@extends('layouts.app')

@section('title', $group->name . ' - Task Manager')

@section('styles')
    .group-details-page {
    max-width: 1000px;
    margin: 0 auto;
    }

    /* Header Panel */
    .group-header-card {
    background: var(--color-surface, #ffffff);
    border: 1px solid var(--color-border-soft, #e2e8f0);
    border-radius: var(--radius-md, 12px);
    padding: 24px 32px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-soft, 0 4px 6px -1px rgba(0, 0, 0, 0.05));
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    }

    .group-header-card h1 {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 700;
    color: var(--color-ink, #1f2937);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    }

    /* Invite Section */
    .invite-box {
    display: flex;
    gap: 8px;
    align-items: center;
    }

    .invite-input {
    padding: 9px 14px;
    border: 1px solid var(--color-border-soft, #d1d5db);
    border-radius: var(--radius-sm, 8px);
    font-size: 14px;
    outline: none;
    width: 240px;
    }

    .invite-input:focus {
    border-color: var(--color-primary, #047857);
    }

    .btn-invite {
    background: #1e3a2b;
    color: #fff;
    padding: 9px 16px;
    border-radius: var(--radius-sm, 8px);
    font-weight: 600;
    font-size: 13.5px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
    }

    .btn-invite:hover {
    background: #14281e;
    }

    /* Card Section */
    .section-card {
    background: var(--color-surface, #ffffff);
    border: 1px solid var(--color-border-soft, #e2e8f0);
    border-radius: var(--radius-md, 12px);
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: var(--shadow-soft, 0 4px 6px -1px rgba(0, 0, 0, 0.05));
    }

    .section-title {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 700;
    color: var(--color-ink, #1f2937);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    }

    /* Add Task Form Grid */
    .add-task-grid {
    display: grid;
    grid-template-columns: 2fr 1.5fr 1.2fr auto;
    gap: 12px;
    background: var(--color-surface-sunken, #f9fafb);
    padding: 16px;
    border-radius: var(--radius-sm, 8px);
    border: 1px solid var(--color-border-soft, #e5e7eb);
    margin-bottom: 20px;
    align-items: center;
    }

    .form-control {
    padding: 9px 12px;
    border: 1px solid var(--color-border-soft, #d1d5db);
    border-radius: var(--radius-sm, 6px);
    font-size: 13.5px;
    background: #fff;
    width: 100%;
    outline: none;
    }

    .btn-add-task {
    background: var(--color-primary, #047857);
    color: #fff;
    padding: 9px 16px;
    border-radius: var(--radius-sm, 6px);
    font-weight: 600;
    font-size: 13.5px;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.2s;
    }

    .btn-add-task:hover {
    background: #035e43;
    }

    /* Task List */
    .task-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--color-surface-sunken, #f9fafb);
    border: 1px solid var(--color-border-soft, #e5e7eb);
    border-radius: var(--radius-sm, 8px);
    margin-bottom: 10px;
    }

    .task-info {
    display: flex;
    align-items: center;
    gap: 12px;
    }

    .task-title {
    font-weight: 500;
    color: var(--color-ink);
    font-size: 14.5px;
    }

    .assignee-badge {
    background: #e0f2fe;
    color: #0369a1;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 999px;
    }

    /* Members List */
    .members-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    }

    .member-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    background: var(--color-surface-sunken, #f9fafb);
    border: 1px solid var(--color-border-soft, #e5e7eb);
    border-radius: var(--radius-sm, 8px);
    }

    .member-info {
    display: flex;
    align-items: center;
    gap: 10px;
    }

    .member-name {
    font-weight: 600;
    color: var(--color-ink);
    font-size: 14px;
    }

    .member-email {
    font-size: 12.5px;
    color: #6b7280;
    }

    .role-badge {
    padding: 3px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    }

    .role-admin {
    background: #1e3a2b;
    color: #ffffff;
    }

    .role-member {
    background: #e6f4ea;
    color: #137333;
    }

    .alert-msg {
    background: #e6f4ea;
    color: #137333;
    padding: 12px 16px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    }

    @media (max-width: 768px) {
    .add-task-grid {
    grid-template-columns: 1fr;
    }
    }
@endsection

@section('content')
    @php
        $rawMembers = $group->members ?? $group->users ?? collect();
        $allMembers = collect([$group->owner])->merge($rawMembers)->filter()->unique('id');
    @endphp
    <div class="group-details-page">

        @if(session('success'))
            <div class="alert-msg">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-msg" style="background: #fce8e6; color: #c5221f;">✗ {{ session('error') }}</div>
        @endif

        {{-- Group Header --}}
        <div class="group-header-card">
            <div>
                <h1>👥 {{ $group->name }}</h1>
            </div>

            {{-- Invite Member Form --}}
            <form action="{{ route('invitations.send', $group->id) }}" method="POST" class="invite-box">
                @csrf
                <input
                    type="email"
                    name="email"
                    class="invite-input"
                    placeholder="Member email address..."
                    required
                >
                <button type="submit" class="btn-invite">Send Invite</button>
            </form>
        </div>

        {{-- Group Tasks Section --}}
        <div class="section-card">
            <h2 class="section-title">📋 Group Tasks</h2>

            {{-- Add Task Form --}}
            <form action="{{ route('tasks.store') }}" method="POST" class="add-task-grid">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->id }}">

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Task Title..."
                    required
                >

                <select name="assigned_to" class="form-control">
                    <option value="">-- Assign To (Optional) --</option>
                    @foreach($allMembers as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date" class="form-control">

                <button type="submit" class="btn-add-task">+ Add Task</button>
            </form>

            {{-- Tasks List --}}
            @if(isset($group->tasks) && $group->tasks->count() > 0)
                @foreach($group->tasks as $task)
                    <div class="task-item">
                        <div class="task-info">
                            <span class="task-title">{{ $task->name }}</span>
                            @if($task->assignedUser)
                                <span class="assignee-badge">👤 {{ $task->assignedUser->name }}</span>
                            @endif
                        </div>
                        <span class="text-sm text-gray-500">{{ $task->date ? \Carbon\Carbon::parse($task->date)->format('M d') : '' }}</span>
                    </div>
                @endforeach
            @else
                <p style="color: var(--color-ink-muted); text-align: center; margin: 20px 0;">No tasks added to this group yet.</p>
            @endif
        </div>
            @php
                $adminId = $group->user_id ?? $group->created_by ?? $group->owner_id ?? ($group->owner->id ?? null);

                $rawMembers = $group->users ?? $group->members ?? collect();

                if ($group->owner) {
                    $rawMembers->push($group->owner);
                }

                $allMembers = $rawMembers->filter()->unique('id');
            @endphp
        {{-- Members Section --}}
            {{-- Members Section --}}
            <div class="section-card">
                <h2 class="section-title">👥 Group Members ({{ $allMembers->count() }})</h2>
                <div class="members-list">
                    @foreach($allMembers as $member)
                        <div class="member-item">
                            <div class="member-info">
                                <span class="member-name">👤 {{ $member->name }}</span>
                                @if($member->email)
                                    <span class="member-email">({{ $member->email }})</span>
                                @endif
                            </div>

                            @if((int) $member->id === (int) $group->user_id)
                                <span class="role-badge role-admin" style="background: #1e3a2b; color: #ffffff; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700;">
                        ADMIN
                    </span>
                            @else
                                <span class="role-badge role-member" style="background: #e6f4ea; color: #137333; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 600;">
                        MEMBER
                    </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

    </div>
@endsection
