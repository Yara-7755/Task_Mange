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
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <form action="/tasks" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by task name..." value="{{ request('search') }}">
        <select name="category_id">
            <option value="">-- All Categories --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <button type="submit">Search</button>
    </form>

    <div class="section-box" @click="activeSection = 'pending'">
        <div class="section-title">⏳ Pending Tasks</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span class="count-badge">{{ $pendingTasks->count() }}</span>
            <span class="arrow">▸</span>
        </div>
    </div>
    <div class="tasks-grid">

        {{-- قسم Pending --}}
        <div class="task-section pending-section">

    <div class="section-box" @click="activeSection = 'expired'">
        <div class="section-title">⚠️ Expired Tasks</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span class="count-badge">{{ $expiredTasks->count() }}</span>
            <span class="arrow">▸</span>
        </div>
    </div>

    <div class="section-box" @click="activeSection = 'completed'">
        <div class="section-title">✅ Completed Tasks</div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <span class="count-badge">{{ $completedTasks->count() }}</span>
            <span class="arrow">▸</span>
        </div>
    </div>

    <a href="/tasks/create" class="add-btn">+ Add New Task</a>

</div>

<div class="fullscreen-overlay" x-show="activeSection === 'pending'" x-cloak style="display: none;">
    <div class="fullscreen-inner">
        <div class="fullscreen-header">
            <h2>⏳ Pending Tasks</h2>
            <button class="close-btn" @click="activeSection = null">×</button>
        </div>
            <h2><span class="section-badge badge-pending">⏳</span> Pending Tasks</h2>

        @if ($pendingTasks->isEmpty())
            <p class="empty-msg">No pending tasks.</p>
        @endif
            @if($pendingTasks->isEmpty())
                <p class="empty-msg">No pending tasks.</p>
            @endif

        @foreach ($pendingTasks as $task)
            @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
        @endforeach
    </div>
</div>

<div class="fullscreen-overlay" x-show="activeSection === 'expired'" x-cloak style="display: none;">
    <div class="fullscreen-inner">
        <div class="fullscreen-header">
            <h2>⚠️ Expired Tasks</h2>
            <button class="close-btn" @click="activeSection = null">×</button>
        </div>
            @foreach($pendingTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>

        <div class="task-section expired-section">

            <h2><span class="section-badge badge-expired">⚠️</span> Expired Tasks</h2>

            @if($expiredTasks->isEmpty())
                <p class="empty-msg">No expired tasks.</p>
            @endif
        @if ($expiredTasks->isEmpty())
            <p class="empty-msg">No expired tasks.</p>
        @endif

            @foreach($expiredTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>
        @foreach ($expiredTasks as $task)
            @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
        @endforeach
    </div>
</div>

<div class="fullscreen-overlay" x-show="activeSection === 'completed'" x-cloak style="display: none;">
    <div class="fullscreen-inner">
        <div class="fullscreen-header">
            <h2>✅ Completed Tasks</h2>
            <button class="close-btn" @click="activeSection = null">×</button>
        </div>
        <div class="task-section completed-section">

            <h2><span class="section-badge badge-completed">✅</span> Completed Tasks</h2>

            @if($completedTasks->isEmpty())
                <p class="empty-msg">No completed tasks yet.</p>
            @endif
        @if ($completedTasks->isEmpty())
            <p class="empty-msg">No completed tasks yet.</p>
        @endif

        @foreach ($completedTasks as $task)
            @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
        @endforeach
    </div>
</div>

</body>
</html>
            @foreach($completedTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>

    </div>

    <a href="/tasks/create" class="add-btn">+ Add New Task</a>

@endsection
