@extends('layouts.app')

@section('title', 'Add Task - Task Manager')

@section('content')

    <div class="hero-panel">
        <div class="page-header">
            <div class="icon-box">📝</div>
        </div>
        <h1>Task Manager</h1>
        <p class="subtitle">Create a new task and stay organized</p>
    </div>

    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <div class="task-card form-card">
        <form action="/tasks" method="POST">
            @csrf

            <div class="form-row">
                <div>
                    <label for="name">Title of Task</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        placeholder="Enter task title"
                        value="{{ old('name') }}"
                    >
                    @error('name')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="category_id">Category</label>
                    <select name="category_id" id="category_id">
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label for="description">Description</label>
            <textarea
                name="description"
                id="description"
                placeholder="Write task description..."
            >{{ old('description') }}</textarea>
            @error('description')
            <div class="error">{{ $message }}</div>
            @enderror

            <div class="form-row">
                <div>
                    <label for="date">Date</label>
                    <input
                        type="date"
                        name="date"
                        id="date"
                        value="{{ old('date') }}"
                    >
                    @error('date')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority">
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>
            </div>

            <label for="repeat_type">Repeat</label>
            <select name="repeat_type" id="repeat_type" onchange="toggleCustomHours()">
                <option value="none" selected>Does not repeat</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="custom">Custom</option>
            </select>

            <div id="customHoursDiv" style="display:none; margin-top: 14px; padding: 16px; background: var(--color-surface-sunken); border-radius: var(--radius-sm); border: 1px solid var(--color-border-soft);">
                <label style="display: flex; align-items: center; gap: 10px; margin-top: 0;">
                    🔁 Repeat every
                    <input
                        type="number"
                        name="repeat_interval_value"
                        id="repeat_interval_value"
                        min="1"
                        value="1"
                        style="width: 80px; text-align: center;"
                    >
                    <select name="repeat_interval_unit" id="repeat_interval_unit" style="width: auto;">
                        <option value="minutes">Minutes</option>
                        <option value="hours">Hours</option>
                    </select>
                </label>
            </div>

            <label for="tags">Tags (comma separated)</label>
            <input
                type="text"
                name="tags"
                id="tags"
                placeholder="e.g. urgent, client, presentation"
                value="{{ old('tags') }}"
            >

            <div class="checkbox">
                <input type="checkbox" name="completed" id="completed" value="1">
                <label for="completed">Completed</label>
            </div>

            <div class="btn-row">
                <button type="submit" class="main-btn main-dark">Save Task</button>
                <a href="/tasks" class="main-btn main-light">Show My Tasks</a>
            </div>
        </form>
    </div>

    <script>
        function toggleCustomHours() {
            const select = document.getElementById('repeat_type');
            const div = document.getElementById('customHoursDiv');
            div.style.display = select.value === 'custom' ? 'block' : 'none';
        }
    </script>

@endsection
