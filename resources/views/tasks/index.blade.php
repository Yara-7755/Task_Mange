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

            <strong>
                {{ $completedTasksCount }} / {{ $totalTasksCount }} completed
                ({{ $progressPercentage }}%)
            </strong>
        </div>

        <div class="progress-bar-bg">
            <div
                class="progress-bar-fill"
                style="width: {{ $progressPercentage }}%;">
            </div>
        </div>
    </div>


    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif


    <form action="/tasks" method="GET" class="search-form">

        <input
            type="text"
            name="search"
            placeholder="Search by task name..."
            value="{{ request('search') }}">

        <select name="category_id">
            <option value="">-- All Categories --</option>

            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Search</button>
    </form>


    <div class="section-box" @click="activeSection = 'pending'">
        <div class="section-title">
            ⏳ Pending Tasks
        </div>

        <div style="display: flex; align-items: center; gap: 15px;">
        <span class="count-badge">
            {{ $pendingTasks->count() }}
        </span>

            <span class="arrow">▸</span>
        </div>
    </div>


    <div class="tasks-grid">

        {{-- ========================= --}}
        {{-- Pending Tasks              --}}
        {{-- ========================= --}}

        <div class="task-section pending-section">

            @if($pendingTasks->isEmpty())
                <p class="empty-msg">
                    No pending tasks.
                </p>
            @else

                @foreach ($pendingTasks as $task)
                    @include('tasks._task-card', [
                        'task' => $task,
                        'categories' => $categories
                    ])
                @endforeach

            @endif

        </div>


        {{-- ========================= --}}
        {{-- Expired Tasks              --}}
        {{-- ========================= --}}

        <div class="section-box" @click="activeSection = 'expired'">
            <div class="section-title">
                ⚠️ Expired Tasks
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
            <span class="count-badge">
                {{ $expiredTasks->count() }}
            </span>

                <span class="arrow">▸</span>
            </div>
        </div>


        <div class="task-section expired-section">

            @if($expiredTasks->isEmpty())
                <p class="empty-msg">
                    No expired tasks.
                </p>
            @else

                @foreach($expiredTasks as $task)
                    @include('tasks._task-card', [
                        'task' => $task,
                        'categories' => $categories
                    ])
                @endforeach

            @endif

        </div>


        {{-- ========================= --}}
        {{-- Completed Tasks            --}}
        {{-- ========================= --}}

        <div class="section-box" @click="activeSection = 'completed'">
            <div class="section-title">
                ✅ Completed Tasks
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
            <span class="count-badge">
                {{ $completedTasks->count() }}
            </span>

                <span class="arrow">▸</span>
            </div>
        </div>


        <div class="task-section completed-section">

            @if($completedTasks->isEmpty())
                <p class="empty-msg">
                    No completed tasks yet.
                </p>
            @else

                @foreach($completedTasks as $task)
                    @include('tasks._task-card', [
                        'task' => $task,
                        'categories' => $categories
                    ])
                @endforeach

            @endif

        </div>

    </div>


    <a href="/tasks/create" class="add-btn">
        + Add New Task
    </a>

@endsection
