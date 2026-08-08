<!DOCTYPE html>
<html>
<head>
    <title>My Tasks - Task Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, rgb(15 69 24 / 0.35), #054221);
            min-height: 100vh;
            padding: 40px;
        }

        .container {
            width: 950px;
            margin: auto;
            background: white;
            padding: 45px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        }

        h1 {
            text-align: center;
            color: #114525;
            font-size: 34px;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 25px;
        }

        /* فورم البحث */
        .search-form {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .search-form input,
        .search-form select {
            padding: 14px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            font-size: 16px;
        }

        .search-form input {
            flex: 1;
        }

        .search-form button {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            background: #164e2e;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        /* الصندوق المقفول (Collapsed) */
        .section-box {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.25s;
        }

        .section-box:hover {
            border-color: #164e2e;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            color: #114525;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .count-badge {
            background: #164e2e;
            color: white;
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 14px;
            font-weight: bold;
        }

        .arrow {
            font-size: 20px;
            color: #164e2e;
        }

        /* العرض الفل سكرين */
        .fullscreen-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgb(15 69 24 / 0.35), #054221);
            z-index: 999;
            overflow-y: auto;
            padding: 40px;
        }

        .fullscreen-inner {
            max-width: 900px;
            margin: auto;
            background: white;
            border-radius: 25px;
            padding: 45px;
        }

        .fullscreen-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .fullscreen-header h2 {
            color: #114525;
            font-size: 28px;
        }

        .close-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: none;
            background: #dc2626;
            color: white;
            font-size: 22px;
            cursor: pointer;
        }

        .empty-msg {
            text-align: center;
            color: #999;
            padding: 30px;
        }

        .add-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            border-radius: 12px;
            background: linear-gradient(135deg, #164e2e, #114525);
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body x-data="{ activeSection: null }">

<div class="container">

    <h1>📝 My Tasks</h1>
    <p class="subtitle">Manage all your tasks in one place</p>

            @if (session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            <form action="/tasks" method="GET" class="search-form" id="searchForm">

                <div class="search-wrapper">
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        placeholder="Search by task name..."
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                    <button type="button" id="clearBtn" class="clear-btn" onclick="clearSearch()">✕</button>
                </div>

                <select name="filter_type" id="filterType">
                    <option value="category" {{ request('filter_type', 'category') == 'category' ? 'selected' : '' }}>Category</option>
                    <option value="priority" {{ request('filter_type') == 'priority' ? 'selected' : '' }}>Priority</option>
                </select>

                <select name="filter_value" id="filterValue">
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

                @if ($expiredTasks->isNotEmpty())
                    <form action="/tasks/clear-expired" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete all expired tasks? This cannot be undone.')" style="margin-bottom: 20px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; padding: 12px; background: #dc2626; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">
                            🗑️ Clear All Expired Tasks
                        </button>
                    </form>
                @endif

                @if ($expiredTasks->isEmpty())
                    <p class="empty-msg">No expired tasks.</p>
                @endif
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

                @if ($completedTasks->isNotEmpty())
                    <form action="/tasks/clear-completed" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete all completed tasks? This cannot be undone.')" style="margin-bottom: 20px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; padding: 12px; background: #dd4e4e; color: white; border: none; border-radius: 10px; font-weight: bold; cursor: pointer;">
                            🗑️ Clear All Completed Tasks
                        </button>
                    </form>
                @endif

                @if ($completedTasks->isEmpty())
                    <p class="empty-msg">No completed tasks yet.</p>
                @endif
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

            @foreach($completedTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach

        </div>

    </div>

    <a href="/tasks/create" class="add-btn">+ Add New Task</a>

</body>
</html>
@endsection
