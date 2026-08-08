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
                        <option value="">
                            -- Select Category --
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                            >
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



        <label for="category_id">Category</label>

        <select name="category_id" id="category_id">

            <option value="">
                -- Select Category --
            </option>

            @foreach ($categories as $category)

                <option value="{{ $category->id }}"
                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                >
                    {{ $category->name }}
                </option>

            @endforeach

        </select>

        @error('category_id')
        <div class="error">{{ $message }}</div>
        @enderror
        <label for="repeat_type">Repeat</label>
        <select name="repeat_type" id="repeat_type" onchange="toggleCustomHours()">
            <option value="none" selected>Does not repeat</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="custom">Custom</option>
        </select>

        <div id="customHoursDiv" style="display:none; margin-top: 10px; padding: 12px; background: #f0fdf4; border-radius: 10px; border: 1px solid #86efac;">
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #166534;">
                🔁 Repeat every
                <input
                    type="number"
                    name="repeat_interval_value"
                    id="repeat_interval_value"
                    min="1"
                    value="1"
                    style="width: 70px; padding: 6px 10px; border-radius: 8px; border: 2px solid #86efac; font-weight: bold; text-align: center;"
                >
                <select
                    name="repeat_interval_unit"
                    id="repeat_interval_unit"
                    style="padding: 6px 10px; border-radius: 8px; border: 2px solid #86efac; font-weight: bold;"
                >
                    <option value="minutes">Minutes</option>
                    <option value="hours">Hours</option>
                </select>
            </label>
        </div>

        <script>
            function toggleCustomHours() {
                const select = document.getElementById('repeat_type');
                const div = document.getElementById('customHoursDiv');
                div.style.display = select.value === 'custom' ? 'block' : 'none';
            }
        </script>

<br>
        <label for="priority">Priority</label>
        <select name="priority" id="priority">
            <option value="low">Low</option>
            <option value="medium" selected>Medium</option>
            <option value="high">High</option>
        </select>


            <div class="checkbox">
                <input
                    type="checkbox"
                    name="completed"
                    id="completed"
                    value="1"
                >
                <label for="completed">
                    Completed
                </label>
            </div>

            <button type="submit" class="main-btn main-dark">
                Save Task
            </button>
        </form>

        <div class="btn-row">
            <a href="/tasks" class="main-btn main-light">
                Show My Tasks
            </a>
            <form action="/logout" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="main-btn main-danger">
                    Logout
                </button>
            </form>
        </div>
    </div>
@endsection
