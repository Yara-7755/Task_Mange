<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <style>
        .create-task-page {
            background: #054221;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow-y: auto;
            margin: -1.5rem -1rem;
        }

        .create-task-page * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .create-task-page .bg-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .create-task-page .container {
            position: relative;
            z-index: 1;
            width: 650px;
            background: #ffffff;
            padding: 45px;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .create-task-page h1 {
            text-align: center;
            color: rgb(17 69 37);
            margin-bottom: 35px;
            font-size: 32px;
        }

        .create-task-page label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            color: #114525;
            font-weight: 600;
        }

        .create-task-page input,
        .create-task-page textarea,
        .create-task-page select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: #f9fafb;
            transition: 0.3s;
        }

        .create-task-page input:focus,
        .create-task-page textarea:focus,
        .create-task-page select:focus {
            border-color: #114525;
            background: white;
            outline: none;
            box-shadow: 0 0 10px rgb(50 198 81 / 0.35);
        }

        .create-task-page textarea {
            height: 140px;
            resize: none;
        }

        .create-task-page .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
        }

        .create-task-page .checkbox input {
            width: 18px;
            height: 18px;
            accent-color: #164e2e;
        }

        .create-task-page .checkbox label {
            margin: 0;
            font-weight: 500;
        }

        .create-task-page button {
            width: 100%;
            margin-top: 35px;
            padding: 15px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #164e2e, #114525);
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .create-task-page button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgb(50 198 81 / 0.35);
        }

        .create-task-page .success {
            background: rgba(50,198,81,0.12);
            color: #114525;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 20px;
        }

        .create-task-page .error {
            color: #dc2626;
            font-size: 14px;
            margin-top: 6px;
        }

        .create-task-page .back-btn {
            display: block;
            width: 100%;
            margin-top: 20px;
            padding: 15px;
            border-radius: 12px;
            background: linear-gradient(135deg,#164e2e,#114525);
            color: white;
            text-decoration: none;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
        }

        .create-task-page .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgb(50 198 81 / 0.35);
        }
    </style>

    <div class="create-task-page">

        <svg class="bg-shapes" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice">
            <path d="M0,240 C300,120 600,360 1000,200 C1300,80 1500,200 1600,160 L1600,0 L0,0 Z" fill="rgba(50,198,81,0.18)"/>
            <path d="M0,900 C400,780 700,900 1100,820 C1400,760 1500,860 1600,800 L1600,900 L0,900 Z" fill="rgba(50,198,81,0.12)"/>
            <circle cx="1400" cy="750" r="180" fill="rgba(50,198,81,0.08)"/>
            <circle cx="120" cy="120" r="120" fill="rgba(50,198,81,0.1)"/>
        </svg>

        <div class="container">

            <h1>📝 Task Manager</h1>

            @if (session('success'))
                <div class="success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="/tasks" method="POST">

                @csrf

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

                <label for="tags">Tags (comma separated)</label>
                <input
                    type="text"
                    name="tags"
                    id="tags"
                    placeholder="e.g. urgent, client, presentation"
                    value="{{ isset($task) ? $task->tags->pluck('name')->join(', ') : old('tags') }}"
                >

                <button type="submit">
                    Save Task
                </button>

                <a href="/tasks" class="back-btn">
                    Show My Tasks
                </a>
            </form>
        </div>

    </div>

    <script>
        function toggleCustomHours() {
            const select = document.getElementById('repeat_type');
            const div = document.getElementById('customHoursDiv');
            div.style.display = select.value === 'custom' ? 'block' : 'none';
        }
    </script>
</x-app-layout>
