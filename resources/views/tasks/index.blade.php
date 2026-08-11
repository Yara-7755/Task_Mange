@extends('layouts.app')

@section('title', 'My Tasks - Task Manager')

@section('styles')

    .tag-chip {
    display: inline-block;
    background: var(--color-accent-soft);
    color: var(--color-primary-dark);
    padding: 3px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    }

    .search-wrapper {
    position: relative;
    flex: 1;
    }

    .search-wrapper input {
    width: 100%;
    }

    .clear-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--color-ink-soft);
    font-size: 18px;
    cursor: pointer;
    display: none;
    }

    .search-form {
    display: flex;
    gap: 15px;
    margin-bottom: 32px;
    }

    .search-form select {
    width: auto;
    min-width: 160px;
    }

    .search-form button {
    background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
    white-space: nowrap;
    }

    @media (max-width: 720px) {
    .search-form {
    flex-direction: column;
    }

    .search-form select {
    width: 100%;
    }
    }

@endsection

@section('content')

    <div class="hero-panel">
        <div class="page-header">
            <div class="icon-box">📝</div>
        </div>
        <h1>My Tasks</h1>
        <p class="subtitle">Manage all your tasks in one place</p>
    </div>

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

        <select name="filter_value" id="filterValue"></select>

        <button type="submit">Search</button>
    </form>

    <div class="tasks-grid">

        <!-- Pending -->
        <div class="task-section pending-section">
            <h2><span class="section-badge badge-pending">⏳</span> Pending Tasks</h2>

            @if ($pendingTasks->isEmpty())
                <p class="empty-msg">No pending tasks.</p>
            @endif

            @foreach ($pendingTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach
        </div>

        <!-- Expired -->
        <div class="task-section expired-section">
            <h2><span class="section-badge badge-expired">⚠️</span> Expired Tasks</h2>

            @if ($expiredTasks->isNotEmpty())
                <form action="/tasks/clear-expired" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete all expired tasks? This cannot be undone.')" style="margin-bottom: 20px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="main-btn main-danger">🗑️ Clear All Expired Tasks</button>
                </form>
            @endif

            @if ($expiredTasks->isEmpty())
                <p class="empty-msg">No expired tasks.</p>
            @endif

            @foreach ($expiredTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach
        </div>

        <!-- Completed -->
        <div class="task-section completed-section">
            <h2><span class="section-badge badge-completed">✅</span> Completed Tasks</h2>

            @if ($completedTasks->isNotEmpty())
                <form action="/tasks/clear-completed" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete all completed tasks? This cannot be undone.')" style="margin-bottom: 20px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="main-btn main-danger">🗑️ Clear All Completed Tasks</button>
                </form>
            @endif

            @if ($completedTasks->isEmpty())
                <p class="empty-msg">No completed tasks yet.</p>
            @endif

            @foreach ($completedTasks as $task)
                @include('tasks._task-card', ['task' => $task, 'categories' => $categories])
            @endforeach
        </div>

    </div>

    <a href="/tasks/create" class="add-btn">+ Add New Task</a>

    <script>
        let debounceTimer;

        const searchInput  = document.getElementById('searchInput');
        const clearBtn     = document.getElementById('clearBtn');
        const searchForm   = document.getElementById('searchForm');
        const filterType   = document.getElementById('filterType');
        const filterValue  = document.getElementById('filterValue');

        searchInput.addEventListener('input', function () {
            clearBtn.style.display = this.value ? 'block' : 'none';
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => searchForm.submit(), 500);
        });

        function clearSearch() {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            searchForm.submit();
        }

        window.addEventListener('DOMContentLoaded', () => {
            if (searchInput.value) clearBtn.style.display = 'block';
        });

        const categories = @json($categories);
        const priorities = [
            { id: 'low',    name: 'Low' },
            { id: 'medium', name: 'Medium' },
            { id: 'high',   name: 'High' },
        ];

        const savedFilterValue = "{{ request('filter_value') }}";

        function populateFilterValue() {
            filterValue.innerHTML = '';

            const allOption = document.createElement('option');
            allOption.value = '';
            allOption.text = filterType.value === 'category' ? '-- All Categories --' : '-- All Priorities --';
            filterValue.appendChild(allOption);

            const items = filterType.value === 'category' ? categories : priorities;

            items.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.text = item.name;
                if (String(item.id) === savedFilterValue) option.selected = true;
                filterValue.appendChild(option);
            });
        }

        populateFilterValue();

        filterType.addEventListener('change', populateFilterValue);
        filterValue.addEventListener('change', () => searchForm.submit());
    </script>

@endsection
