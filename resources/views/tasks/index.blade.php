<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks - Task Manager</title>

    <style>
        /* ===== Reset ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ===== Variables ===== */
        :root {
            --color-primary-dark: #114525;
            --color-primary: #164e2e;
            --color-accent: rgb(50,198,81);
            --color-icon-bg: rgba(50,198,81,0.12);
            --color-text: #114525;
            --color-text-muted: #6b7280;
            --color-label: #114525;
            --color-border: #e5e7eb;
            --color-bg-input: #f9fafb;
            --color-success-bg: rgba(50,198,81,0.12);
            --color-success-text: #114525;
            --color-danger: #e24b4a;
            --color-danger-dark: #991b1b;
            --radius-lg: 25px;
            --radius-md: 12px;
        }

        /* ===== Layout ===== */
        body {
            background: #032d17;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow-x: hidden;
        }

        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 950px;
            margin: auto;
            background: #cedfce;
            padding: 45px;
            border-radius: var(--radius-lg);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        /* ===== Header ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .icon-box {
            font-size: 45px;
        }

        h1 {
            color: var(--color-primary-dark);
            font-size: 32px;
            font-weight: 700;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: var(--color-text-muted);
            margin-bottom: 35px;
            font-size: 15px;
        }

        h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--color-text);
            font-size: 18px;
            font-weight: 600;
            margin-top: 40px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--color-border);
            padding-bottom: 12px;
        }

        .section-badge {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .badge-pending {
            background: var(--color-icon-bg);
            color: var(--color-primary-dark);
        }

        .badge-expired {
            background: #fee2e2;
            color: var(--color-danger-dark);
        }

        .badge-completed {
            background: rgba(50,198,81,0.15);
            color: var(--color-primary-dark);
        }

        /* ===== Messages ===== */
        .success {
            background: var(--color-success-bg);
            color: var(--color-success-text);
            padding: 15px;
            border-radius: var(--radius-md);
            text-align: center;
            margin-bottom: 25px;
            font-size: 14px;
        }
        /* ===== Progress Bar ===== */
        .progress-section {
            margin-bottom: 30px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 14px;
            color: var(--color-text-muted);
        }

        .progress-label strong {
            color: var(--color-text);
            font-size: 15px;
        }

        .progress-bar-bg {
            width: 100%;
            height: 14px;
            background: var(--color-border);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(135deg, #164e2e, #114525);
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .empty-msg {
            text-align: center;
            color: var(--color-text-muted);
            padding: 15px;
            font-size: 14px;
        }

        /* ===== Task Card ===== */
        .task-card {
            background: var(--color-bg-input);
            border: 1px solid var(--color-border);
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 20px;
        }

        .task-card label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            color: var(--color-label);
            font-weight: 600;
            font-size: 14px;
        }

        input, textarea, select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--color-border);
            border-radius: var(--radius-md);
            background: var(--color-bg-input);
            font-size: 16px;
            transition: 0.3s;
        }

        textarea {
            height: 120px;
            resize: none;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--color-primary-dark);
            background: white;
            box-shadow: 0 0 10px rgb(50 198 81 / 0.35);
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
        }

        .checkbox input {
            width: 18px;
            height: 18px;
            accent-color: var(--color-primary-dark);
        }

        .checkbox label {
            margin: 0;
        }

        /* ===== Actions / Buttons ===== */
        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 22px;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        button {
            padding: 14px 24px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            color: white;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .update-btn {
            order: 1;
            background: linear-gradient(135deg, #164e2e, #114525);
        }

        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgb(50 198 81 / 0.35);
        }

        .delete-btn {
            order: 2;
            background: var(--color-danger);
        }

        .delete-btn:hover {
            background: var(--color-danger-dark);
        }

        .add-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #164e2e, #114525);
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgb(50 198 81 / 0.35);
        }
        .task-section{
            border:2px solid #e5e7eb;
            border-radius:20px;
            padding:25px;
            margin-bottom:35px;
            background:#ffffff;
        }

        .task-section h2{
            text-align:center;
            color:#114525;
            font-size:24px;
            margin:0 0 25px 0;
            padding-bottom:15px;
            border-bottom:3px solid #e5e7eb;
        }

        .pending-section{
            border-color:#32c651;
        }

        .expired-section{
            border-color:#dc2626;
        }

        .completed-section{
            border-color:#164e2e;
        }
        .task-section{
            background:white;
            border-radius:25px;
            padding:30px;
            margin-bottom:35px;
            box-shadow:0 10px 30px rgba(0,0,0,0.15);
            border:2px solid #e5e7eb;
            .task-card {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 20px;
                padding: 25px;
                margin-bottom: 20px;
                transition: .3s;
            }

            .task-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 25px rgba(0,0,0,.12);
            }


            .task-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }


            .task-header h3 {
                color: #114525;
                font-size: 22px;
            }


            .priority {
                padding: 6px 15px;
                border-radius: 20px;
                color: white;
                font-size: 14px;
                font-weight: bold;
            }

            .priority.high {
                background: #dc2626;
            }

            .priority.medium {
                background: #f59e0b;
            }

            .priority.low {
                background: #32c651;
            }


            .description {
                margin: 18px 0;
                color: #555;
                line-height: 1.6;
            }


            .task-info {
                display: flex;
                gap: 20px;
                color: #6b7280;
                margin-bottom: 20px;
            }


            .actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            }


            .actions button,
            .edit-btn {
                padding: 10px 18px;
                border-radius: 12px;
                border: none;
                cursor: pointer;
                color: white;
                font-weight: bold;
                text-decoration: none;
            }


            .complete-btn {
                background: #32c651;
            }


            .edit-btn {
                background: #164e2e;
            }


            .delete-btn {
                background: #dc2626;
            }
        }
    </style>
</head>

<body>

<svg class="bg-shapes" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice">
    <path d="M0,240 C300,120 600,360 1000,200 C1300,80 1500,200 1600,160 L1600,0 L0,0 Z" fill="rgba(50,198,81,0.18)"/>
    <path d="M0,900 C400,780 700,900 1100,820 C1400,760 1500,860 1600,800 L1600,900 L0,900 Z" fill="rgba(50,198,81,0.12)"/>
    <circle cx="1400" cy="750" r="180" fill="rgba(50,198,81,0.08)"/>
    <circle cx="120" cy="120" r="120" fill="rgba(50,198,81,0.1)"/>
</svg>

<div class="container">

    <div class="page-header">
        <div class="icon-box">📋</div>
    </div>
    <h1>My Tasks</h1>
    <p class="subtitle">Manage all your tasks in one place</p>
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

    {{-- قسم Pending --}}
    <div class="task-section">

        <h2>⏳ Pending Tasks</h2>

        @if($pendingTasks->isEmpty())
            <p class="empty-msg">No pending tasks.</p>
        @endif

        @foreach($pendingTasks as $task)
            @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
        @endforeach

    </div>



    <div class="task-section expired-section">

        <h2>⚠️ Expired Tasks</h2>

        @if($expiredTasks->isEmpty())
            <p class="empty-msg">No expired tasks.</p>
        @endif

        @foreach($expiredTasks as $task)
            @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
        @endforeach

    </div>



    <div class="task-section completed-section">

        <h2>✅ Completed Tasks</h2>

        @if($completedTasks->isEmpty())
            <p class="empty-msg">No completed tasks yet.</p>
        @endif

        @foreach($completedTasks as $task)
            @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
        @endforeach

    </div>

    <a href="/tasks/create" class="add-btn">+ Add New Task</a>

</div>

</body>
</html>
