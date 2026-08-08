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

            <div class="form-row">
                <div>
                    <label for="date">Date</label>
                    <input
                        type="date"
                        name="date"
                        id="date"
                        value="{{ old('date') }}"
                        min="{{ now()->toDateString() }}"
                    >
                    @error('date')
                    <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

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
