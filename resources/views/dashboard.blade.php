@extends('layouts.app')

@section('title', 'Dashboard - Task Manager')


@section('styles')

    .dashboard-page {
    max-width: 1200px;
    margin: 0 auto;
    }


    /* =========================
    Today's Tasks
    ========================= */

    .today-section {
    margin-bottom: 24px;
    }

    .today-section h2 {
    margin-bottom: 20px;
    }


    /* =========================
    Task Rows
    ========================= */

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


    /* =========================
    Two Columns
    ========================= */

    .dashboard-two-column {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;

    margin-bottom: 24px;
    }

    .dashboard-two-column .task-section {
    margin-bottom: 0;
    }


    /* =========================
    Priority
    ========================= */

    .priority-pill {
    padding: 4px 12px;

    border-radius: 999px;

    color: #fff;

    font-size: 11px;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 0.04em;
    }

    .priority-pill.high {
    background: var(--color-danger);
    }

    .badge-priority {
    background: var(--color-warning-soft);
    color: var(--color-warning);
    }

    .task-section.priority-border {
    border-top: 3px solid var(--color-warning);
    }


    /* =========================
    Tags
    ========================= */

    .badge-tags {
    background: var(--color-accent-soft);
    color: var(--color-primary-dark);
    }

    .task-section.tags-border {
    border-top: 3px solid var(--color-primary);
    }

    .tags-wrap {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-start;
    }

    .tag-chip {
    text-decoration: none;

    background: var(--color-accent-soft);
    color: var(--color-primary-dark);

    padding: 8px 16px;

    border-radius: 999px;

    font-weight: 600;
    font-size: 13.5px;

    transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
    }

    .tag-chip:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-card-hover);
    }


    /* =========================
    Today's Tasks Border
    ========================= */

    .task-section.pending-border {
    border-top: 3px solid var(--color-accent);
    }


    /* =========================
    Quick Stats
    ========================= */

    .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);

    gap: 20px;

    margin-bottom: 24px;
    }

    .stat-card {
    background: var(--color-surface-alt);

    border: 1px solid var(--color-border-soft);

    border-radius: var(--radius-md);

    padding: 20px;

    text-align: center;

    box-shadow: var(--shadow-soft);
    }

    .stat-card .stat-number {
    font-family: var(--font-display);

    font-size: 32px;
    font-weight: 700;
    }

    .stat-card .stat-label {
    color: var(--color-ink-muted);

    margin-top: 6px;

    font-size: 14px;
    }

    .stat-pending .stat-number {
    color: var(--color-primary);
    }

    .stat-expired .stat-number {
    color: var(--color-danger);
    }

    .stat-completed .stat-number {
    color: var(--color-accent);
    }


    /* =========================
    Focus Timer Button
    ========================= */

    .focus-timer-btn {
    display: flex;

    align-items: center;
    justify-content: center;

    width: 100%;

    padding: 18px 24px;

    margin: 0 0 24px;

    background: var(--color-surface-alt);

    border: 1px solid var(--color-border-soft);

    border-radius: var(--radius-md);

    color: var(--color-ink);

    text-decoration: none;

    font-family: var(--font-display);

    font-size: 18px;
    font-weight: 700;

    box-shadow: var(--shadow-soft);

    transition:
    transform 0.2s ease,
    box-shadow 0.2s ease,
    background 0.2s ease;
    }

    .focus-timer-btn:hover {
    transform: translateY(-2px);

    box-shadow: var(--shadow-card-hover);

    background: var(--color-surface);
    }


    /* =========================
    Responsive
    ========================= */

    @media (max-width: 720px) {

    .dashboard-two-column {
    grid-template-columns: 1fr;
    }

    .stats-grid {
    grid-template-columns: 1fr;
    }

    .task-row {
    gap: 10px;
    align-items: flex-start;
    }

    }

@endsection


@section('content')

    <div class="dashboard-page">

        <!-- ==========================================
             Hero
             ========================================== -->

        <div class="hero-panel">

            <div class="page-header">
                <div class="icon-box">🏠</div>
            </div>

            <h1>Dashboard</h1>

            <p class="subtitle">
                Here's what's on your plate today
            </p>

        </div>


        <!-- ==========================================
             1. Today's Tasks - Most Important
             ========================================== -->

        <div class="task-section today-section pending-border">

            <h2>
            <span class="section-badge badge-pending">
                📌
            </span>

                Today's Tasks
            </h2>


            @if ($todaysTasks->isEmpty())

                <p class="empty-msg">
                    No tasks due today.
                </p>

            @else

                @foreach ($todaysTasks as $task)

                    <div class="task-row">

                    <span class="task-name">
                        {{ $task->name }}
                    </span>


                        @if ($task->category)

                            <span class="task-date">
                            {{ $task->category->name }}
                        </span>

                        @endif

                    </div>

                @endforeach

            @endif

        </div>


        <!-- ==========================================
             2. Quick Stats
             ========================================== -->

        <div class="stats-grid">

            <div class="stat-card stat-pending">

                <div class="stat-number">
                    {{ $pendingCount }}
                </div>

                <div class="stat-label">
                    ⏳ Pending Tasks
                </div>

            </div>


            <div class="stat-card stat-expired">

                <div class="stat-number">
                    {{ $expiredCount }}
                </div>

                <div class="stat-label">
                    ⚠️ Expired Tasks
                </div>

            </div>


            <div class="stat-card stat-completed">

                <div class="stat-number">
                    {{ $completedTodayCount }}
                </div>

                <div class="stat-label">
                    ✅ Completed Today
                </div>

            </div>

        </div>


        <!-- ==========================================
             3 + 4. High Priority + Top Tags
             ========================================== -->

        <div class="dashboard-two-column">


            <!-- High Priority -->

            <div class="task-section priority-border">

                <h2>

                <span class="section-badge badge-priority">
                    🔥
                </span>

                    High Priority

                </h2>


                @if ($highPriorityTasks->isEmpty())

                    <p class="empty-msg">
                        No high priority tasks right now.
                    </p>

                @else

                    @foreach ($highPriorityTasks as $task)

                        <div class="task-row">

                        <span class="task-name">
                            {{ $task->name }}
                        </span>


                            <span style="
                            display: flex;
                            align-items: center;
                            gap: 10px;
                        ">

                            <span class="priority-pill high">
                                High
                            </span>


                            @if ($task->date)

                                    <span class="task-date">
                                    {{ \Carbon\Carbon::parse($task->date)->format('M d') }}
                                </span>

                                @endif

                        </span>

                        </div>

                    @endforeach

                @endif

            </div>


            <!-- Top Tags -->

            <div class="task-section tags-border">

                <h2>

                <span class="section-badge badge-tags">
                    🏷️
                </span>

                    Top Tags

                </h2>


                @if ($topTags->isEmpty())

                    <p class="empty-msg">
                        No tags used yet.
                    </p>

                @else

                    <div class="tags-wrap">

                        @foreach ($topTags as $tag)

                            <a
                                href="/tasks?search={{ urlencode($tag->name) }}"
                                class="tag-chip"
                            >

                                #{{ $tag->name }}
                                ({{ $tag->tasks_count }})

                            </a>

                        @endforeach

                    </div>

                @endif

            </div>

        </div>


        <!-- ==========================================
             5. Focus Timer
             ========================================== -->

        <a
            href="{{ route('tasks.timer') }}"
            class="focus-timer-btn"
        >
            ⬜ Focus Timer
        </a>


        <!-- ==========================================
             View All Tasks
             ========================================== -->

        <a href="/tasks" class="add-btn">
            View All Tasks
        </a>

    </div>

@endsection
