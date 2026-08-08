<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <div style="display: flex; gap: 20px; margin-bottom: 30px; max-width: 900px; margin-left: auto; margin-right: auto;">

            <div style="flex: 1; background: white; border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <div style="font-size: 32px; font-weight: bold; color: #164e2e;">{{ $pendingCount }}</div>
                <div style="color: #666; margin-top: 5px;">⏳ Pending Tasks</div>
            </div>

            <div style="flex: 1; background: white; border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <div style="font-size: 32px; font-weight: bold; color: #dc2626;">{{ $expiredCount }}</div>
                <div style="color: #666; margin-top: 5px;">⚠️ Expired Tasks</div>
            </div>

            <div style="flex: 1; background: white; border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <div style="font-size: 32px; font-weight: bold; color: #16a34a;">{{ $completedTodayCount }}</div>
                <div style="color: #666; margin-top: 5px;">✅ Completed Today</div>
            </div>

        </div>
        @if ($highPriorityTasks->count())
            <div style="max-width: 900px; margin: 0 auto 30px; background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <h3 style="color: #114525; margin-bottom: 15px;">🔥 High Priority Tasks</h3>

                @foreach ($highPriorityTasks as $task)
                    <div style="padding: 12px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                        <span>{{ $task->name }}</span>
                        @if ($task->date)
                            <span style="color: #999; font-size: 13px;">{{ \Carbon\Carbon::parse($task->date)->format('M d') }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if ($topTags->count())
            <div style="max-width: 900px; margin: 0 auto 30px; background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                <h3 style="color: #114525; margin-bottom: 15px;">🏷️ Top Tags</h3>

                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach ($topTags as $tag)
                        <a href="/tasks?search={{ $tag->name }}" style="text-decoration: none; background: #e0f2e9; color: #166534; padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 14px;">
                            #{{ $tag->name }} ({{ $tag->tasks_count }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div style="max-width: 550px; margin: 40px auto; background: white; border-radius: 240px; padding: 55px 45px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">
            <h2 style="font-size: 26px; margin-bottom: 25px; color: #114525;">⏱️ Focus Timer</h2>

            <select id="taskSelect" style="width: 100%; padding: 12px; border-radius: 10px; border: 2px solid #e5e7eb; margin-bottom: 35px; font-size: 16px;">
                <option value="">-- Select a task --</option>
                @foreach ($pendingTasks as $task)
                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                @endforeach
            </select>

            <div style="position: relative; width: 320px; height: 320px; margin: 0 auto 35px;">
                <svg width="320" height="320" style="transform: rotate(-90deg);">
                    <circle
                        cx="160" cy="160" r="140"
                        fill="none"
                        stroke="#e5e7eb"
                        stroke-width="18"
                    />
                    <circle
                        id="progressCircle"
                        cx="160" cy="160" r="140"
                        fill="none"
                        stroke="#9ca3af"
                        stroke-width="18"
                        stroke-linecap="round"
                        stroke-dasharray="879.6"
                        stroke-dashoffset="0"
                        style="transition: stroke-dashoffset 1s linear, stroke 0.4s ease;"
                    />
                </svg>

                <div id="timerDisplay" style="
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            font-size: 56px;
            font-weight: bold;
            color: #164e2e;
            font-family: monospace;
        ">
                    25:00
                </div>
            </div>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <button id="startBtn" style="padding: 16px 34px; border: none; border-radius: 12px; background: #164e2e; color: white; font-size: 17px; font-weight: bold; cursor: pointer;">Start</button>
                <button id="pauseBtn" style="padding: 16px 34px; border: none; border-radius: 12px; background: #f59e0b; color: white; font-size: 17px; font-weight: bold; cursor: pointer;">Pause</button>
                <button id="resetBtn" style="padding: 16px 34px; border: none; border-radius: 12px; background: #dc2626; color: white; font-size: 17px; font-weight: bold; cursor: pointer;">Reset</button>
            </div>

        </div>

        <script>
            let totalSeconds = 25 * 60;
            let elapsedSeconds = 0;
            let timerInterval = null;
            let isRunning = false;

            const display = document.getElementById('timerDisplay');
            const progressCircle = document.getElementById('progressCircle');
            const startBtn = document.getElementById('startBtn');
            const pauseBtn = document.getElementById('pauseBtn');
            const resetBtn = document.getElementById('resetBtn');
            const taskSelect = document.getElementById('taskSelect');

            const CIRCLE_LENGTH = 879.6;
            function setCircleColor(color) {
                progressCircle.style.stroke = color;
            }
            function updateDisplay() {
                let remaining = totalSeconds - elapsedSeconds;
                let minutes = Math.floor(remaining / 60);
                let seconds = remaining % 60;
                display.textContent =
                    String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');

                let progress = elapsedSeconds / totalSeconds;
                let offset = CIRCLE_LENGTH * progress;
                progressCircle.style.strokeDashoffset = offset;
            }

            startBtn.addEventListener('click', function () {
                if (isRunning) return;
                if (!taskSelect.value) {
                    alert('Please select a task first');
                    return;
                }
                isRunning = true;
                setCircleColor('#164e2e');

                timerInterval = setInterval(function () {
                    elapsedSeconds++;
                    updateDisplay();

                    if (elapsedSeconds >= totalSeconds) {
                        clearInterval(timerInterval);
                        isRunning = false;
                        saveTime();
                        alert('Time is up! Great work 🎉');
                    }
                }, 1000);
            });

            pauseBtn.addEventListener('click', function () {
                clearInterval(timerInterval);
                isRunning = false;
                setCircleColor('#f59e0b');
                saveTime();
            });
            resetBtn.addEventListener('click', function () {
                clearInterval(timerInterval);
                isRunning = false;
                elapsedSeconds = 0;
                setCircleColor('#9ca3af');
                updateDisplay();
            });

            function saveTime() {
                if (elapsedSeconds === 0 || !taskSelect.value) return;

                fetch(`/tasks/${taskSelect.value}/add-time`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ seconds: elapsedSeconds }),
                });

                elapsedSeconds = 0;
            }
        </script>
    </x-slot>
@extends('layouts.app')

@section('title', 'Dashboard - Task Manager')

@section('content')

    <div class="hero-panel">
        <div class="page-header">
            <div class="icon-box">🏠</div>
        </div>
        <h1>Dashboard</h1>
        <p class="subtitle">Here's what's on your plate today</p>
    </div>

    <div class="task-section">

        <h2><span class="section-badge badge-pending">📌</span> Today's Tasks</h2>

        @if($todaysTasks->isEmpty())
            <p class="empty-msg">No tasks due today.</p>
        @endif

        @foreach($todaysTasks as $task)
            <div class="task-card" style="padding: 16px 22px;">
                <h3 style="font-family: var(--font-display); color: var(--color-ink); font-size: 17px; font-weight: 600;">
                    {{ $task->name }}
                </h3>
            </div>
        @endforeach

    </div>

    <a href="/tasks" class="add-btn">View All Tasks</a>

@endsection
