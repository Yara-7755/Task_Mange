@extends('layouts.app')

@section('title', 'My Tasks - Task Manager')

@section('content')

    <div class="hero-panel">
        <div class="page-header">
            <div class="icon-box">📋</div>
        </div>
        <h1>My Tasks</h1>
        <p class="subtitle">Manage all your tasks in one place</p>
    </div>

    <div class="progress-section">
        <div class="progress-label">
            <span>Progress</span>
            <strong>{{ $completedTasksCount }} / {{ $totalTasksCount }} completed ({{ $progressPercentage }}%)</strong>
        </div>
        <div class="progress-bar-bg">
            <div class="progress-bar-fill" style="width: {{ $progressPercentage }}%;"></div>
        </div>
    </div>
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="tasks-grid">

        {{-- قسم Pending --}}
        <div class="task-section pending-section">

            <h2><span class="section-badge badge-pending">⏳</span> Pending Tasks</h2>

            @if($pendingTasks->isEmpty())
                <p class="empty-msg">No pending tasks.</p>
            @endif

            @foreach($pendingTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>

        <div class="task-section expired-section">

            <h2><span class="section-badge badge-expired">⚠️</span> Expired Tasks</h2>

            @if($expiredTasks->isEmpty())
                <p class="empty-msg">No expired tasks.</p>
            @endif

            @foreach($expiredTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>

        <div class="task-section completed-section">

            <h2><span class="section-badge badge-completed">✅</span> Completed Tasks</h2>

            @if($completedTasks->isEmpty())
                <p class="empty-msg">No completed tasks yet.</p>
            @endif

            @foreach($completedTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>

    </div>

    <a href="/tasks/create" class="add-btn">+ Add New Task</a>

@endsection
