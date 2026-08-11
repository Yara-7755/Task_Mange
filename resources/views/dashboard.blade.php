@extends('layouts.app')

@section('title', 'Dashboard - Task Manager')

@section('styles')

    /* أنماط إضافية خاصة بالداشبورد فقط، الباقي مأخوذ جاهز من layouts/app.blade.php */

    .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 32px;
    }

    @media (max-width: 720px) {
    .stats-grid {
    grid-template-columns: 1fr;
    }
    }

    .stat-card {
    background: var(--color-surface-alt);
    border: 1px solid var(--color-border-soft);
    border-radius: var(--radius-md);
    padding: 24px;
    text-align: center;
    box-shadow: var(--shadow-soft);
    }

    .stat-card .stat-number {
    font-family: var(--font-display);
    font-size: 34px;
    font-weight: 700;
    }

    .stat-card .stat-label {
    color: var(--color-ink-muted);
    margin-top: 6px;
    font-size: 14px;
    }

    .stat-pending .stat-number { color: var(--color-primary); }
    .stat-expired .stat-number { color: var(--color-danger); }
    .stat-completed .stat-number { color: var(--color-accent); }

    .badge-priority { background: var(--color-warning-soft); color: var(--color-warning); }
    .badge-tags { background: var(--color-accent-soft); color: var(--color-primary-dark); }

    .task-section.pending-border { border-top: 3px solid var(--color-accent); }
    .task-section.priority-border { border-top: 3px solid var(--color-warning); }
    .task-section.tags-border { border-top: 3px solid var(--color-primary); }

    .task-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: var(--color-surface-sunken);
    border: 1px solid var(--color-border-soft);
    border-radius: var(--radius-sm);
    margin-bottom: 12px;
    }

    .task-row:last-child {
    margin-bottom: 0;
    }

    .task-row .task-name {
    font-weight: 500;
    color: var(--color-ink);
    }

    .task-row .task-date {
    color: var(--color-ink-soft);
    font-size: 12.5px;
    font-family: var(--font-mono);
    }

    .priority-pill {
    padding: 4px 12px;
    border-radius: 999px;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    }

    .priority-pill.high { background: var(--color-danger); }

    .tags-wrap {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    }

    .tag-chip {
    text-decoration: none;
    background: var(--color-accent-soft);
    color: var(--color-primary-dark);
    padding: 8px 16px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 13.5px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .tag-chip:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-card-hover);
    }

    .timer-panel {
    max-width: 420px;
    margin: 0 auto;
    text-align: center;
    }

    .timer-panel select {
    margin-bottom: 30px;
    }

    #timerDisplay {
    font-family: var(--font-mono);
    }

    .timer-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    }

    .timer-buttons button {
    padding: 14px 28px;
    }

    #startBtn { background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); }
    #pauseBtn { background: var(--color-warning); }
    #resetBtn { background: var(--color-danger); }

@endsection

@section('content')

    <!-- Hero -->
    <div class="hero-panel">
        <div class="page-header">
            <div class="icon-box">🏠</div>
        </div>
        <h1>Dashboard</h1>
        <p class="subtitle">Here's what's on your plate today</p>
    </div>

    <!-- 1. Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card stat-pending">
            <div class="stat-number">{{ $pendingCount }}</div>
            <div class="stat-label">⏳ Pending Tasks</div>
        </div>
        <div class="stat-card stat-expired">
            <div class="stat-number">{{ $expiredCount }}</div>
            <div class="stat-label">⚠️ Expired Tasks</div>
        </div>
        <div class="stat-card stat-completed">
            <div class="stat-number">{{ $completedTodayCount }}</div>
            <div class="stat-label">✅ Completed Today</div>
        </div>
    </div>

    <!-- 2. Today's Tasks -->
    <div class="task-section pending-border">
        <h2><span class="section-badge badge-pending">📌</span> Today's Tasks</h2>

        @if ($todaysTasks->isEmpty())
            <p class="empty-msg">No tasks due today.</p>
        @endif

        @foreach ($todaysTasks as $task)
            <div class="task-row">
                <span class="task-name">{{ $task->name }}</span>
                @if ($task->category)
                    <span class="task-date">{{ $task->category->name }}</span>
                @endif
            </div>
        @endforeach
    </div>

    <!-- 3. High Priority Tasks -->
    <div class="task-section priority-border">
        <h2><span class="section-badge badge-priority">🔥</span> High Priority Tasks</h2>

        @if ($highPriorityTasks->isEmpty())
            <p class="empty-msg">No high priority tasks right now.</p>
        @endif

        @foreach ($highPriorityTasks as $task)
            <div class="task-row">
                <span class="task-name">{{ $task->name }}</span>
                <span style="display: flex; align-items: center; gap: 10px;">
                    <span class="priority-pill high">High</span>
                    @if ($task->date)
                        <span class="task-date">{{ \Carbon\Carbon::parse($task->date)->format('M d') }}</span>
                    @endif
                </span>
            </div>
        @endforeach
    </div>

    <!-- 4. Top Tags -->
    <div class="task-section tags-border">
        <h2><span class="section-badge badge-tags">🏷️</span> Top Tags</h2>

        @if ($topTags->isEmpty())
            <p class="empty-msg">No tags used yet.</p>
        @else
            <div class="tags-wrap">
                @foreach ($topTags as $tag)
                    <a href="/tasks?search={{ $tag->name }}" class="tag-chip">
                        #{{ $tag->name }} ({{ $tag->tasks_count }})
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 5. Focus Timer -->
    <div class="task-section">
        <h2><span class="section-badge badge-completed">⏱️</span> Focus Timer</h2>

        <div class="timer-panel">
            <select id="taskSelect">
                <option value="">-- Select a task --</option>
                @foreach ($pendingTasks as $task)
                    <option value="{{ $task->id }}">{{ $task->name }}</option>
                @endforeach
            </select>

            <div style="position: relative; width: 260px; height: 260px; margin: 0 auto 30px;">
                <svg width="260" height="260" style="transform: rotate(-90deg);">
                    <circle cx="130" cy="130" r="112" fill="none" stroke="var(--color-border)" stroke-width="16" />
                    <circle
                        id="progressCircle"
                        cx="130" cy="130" r="112"
                        fill="none"
                        stroke="var(--color-ink-soft)"
                        stroke-width="16"
                        stroke-linecap="round"
                        stroke-dasharray="703.7"
                        stroke-dashoffset="0"
                        style="transition: stroke-dashoffset 1s linear, stroke 0.4s ease;"
                    />
                </svg>

                <div id="timerDisplay" style="
                    position: absolute;
                    top: 50%; left: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 44px;
                    font-weight: bold;
                    color: var(--color-primary-dark);
                ">
                    25:00
                </div>
            </div>

            <div class="timer-buttons">
                <button id="startBtn" type="button">Start</button>
                <button id="pauseBtn" type="button">Pause</button>
                <button id="resetBtn" type="button">Reset</button>
            </div>
        </div>
    </div>

    <a href="/tasks" class="add-btn">View All Tasks</a>

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

        const CIRCLE_LENGTH = 703.7;

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
            setCircleColor('#1f5c37');

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
            setCircleColor('#c08a2e');
            saveTime();
        });

        resetBtn.addEventListener('click', function () {
            clearInterval(timerInterval);
            isRunning = false;
            elapsedSeconds = 0;
            setCircleColor('#93a099');
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

@endsection
