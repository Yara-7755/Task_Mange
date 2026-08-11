@extends('layouts.app')

@section('title', 'My Tasks - Task Manager')

@push('styles')
    <style>
        .progress-section {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #114525;
        }

        .progress-bar-bg {
            width: 100%;
            height: 12px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: #164e2e;
            transition: width 0.4s ease;
        }

        /* Alpine x-cloak لتجنب أخطاء الوميض قبل تحميل الجافاسكريبت */
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
    <div x-data="{ activeSection: null }">

        {{-- Hero Panel --}}
        <div class="hero-panel">
            <div class="page-header">
                <div class="icon-box">📋</div>
            </div>
            <h1>My Tasks</h1>
            <p class="subtitle">Manage all your tasks in one place</p>
        </div>

        {{-- Progress Bar Section --}}
        <div class="progress-section">
            <div class="progress-label">
                <span>Progress</span>
                <strong>
                    {{ $completedTasksCount ?? 0 }} / {{ $totalTasksCount ?? 0 }} completed
                    ({{ $progressPercentage ?? 0 }}%)
                </strong>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ $progressPercentage ?? 0 }}%;"></div>
            </div>
        </div>

        {{-- Success Flash Message --}}
        @if(session('success'))
            <div class="success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Search Form --}}
        <form action="{{ route('tasks.index') }}" method="GET" class="search-form">
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

        {{-- Interactive Section Cards --}}
        <div class="section-box" @click="activeSection = 'pending'">
            <div class="section-title">⏳ Pending Tasks</div>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span class="count-badge">{{ $pendingTasks->count() }}</span>
                <span class="arrow">▸</span>
            </div>
        </div>

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

        {{-- Fullscreen Overlay Modals --}}
        {{-- 1. Pending Tasks Modal --}}
        <div class="fullscreen-overlay" x-show="activeSection === 'pending'" x-cloak>
            <div class="fullscreen-inner">
                <div class="fullscreen-header">
                    <h2>⏳ Pending Tasks</h2>
                    <button class="close-btn" @click="activeSection = null">×</button>
                </div>

                @if($pendingTasks->isEmpty())
                    <p class="empty-msg">No pending tasks.</p>
                @else
                    @foreach ($pendingTasks as $task)
                        @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
                    @endforeach
                @endif
            </div>
        </div>

        {{-- 2. Expired Tasks Modal --}}
        <div class="fullscreen-overlay" x-show="activeSection === 'expired'" x-cloak>
            <div class="fullscreen-inner">
                <div class="fullscreen-header">
                    <h2>⚠️ Expired Tasks</h2>
                    <button class="close-btn" @click="activeSection = null">×</button>
                </div>

                @if($expiredTasks->isEmpty())
                    <p class="empty-msg">No expired tasks.</p>
                @else
                    @foreach($expiredTasks as $task)
                        @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
                    @endforeach
                @endif
            </div>
        </div>

        {{-- 3. Completed Tasks Modal --}}
        <div class="fullscreen-overlay" x-show="activeSection === 'completed'" x-cloak>
            <div class="fullscreen-inner">
                <div class="fullscreen-header">
                    <h2>✅ Completed Tasks</h2>
                    <button class="close-btn" @click="activeSection = null">×</button>
                </div>

                @if($completedTasks->isEmpty())
                    <p class="empty-msg">No completed tasks yet.</p>
                @else
                    @foreach($completedTasks as $task)
                        @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Add Button --}}
        <a href="{{ route('tasks.create') }}" class="add-btn">+ Add New Task</a>

    </div>
@endsection
