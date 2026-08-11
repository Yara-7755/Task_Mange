<div class="task-card">
    <!-- Update Form -->
    @php
        $repeatValue = null;
        $repeatUnit = 'minutes';
        if ($task->repeat_interval_minutes) {
            if ($task->repeat_interval_minutes % 60 === 0) {
                $repeatValue = $task->repeat_interval_minutes / 60;
                $repeatUnit = 'hours';
            } else {
                $repeatValue = $task->repeat_interval_minutes;
                $repeatUnit = 'minutes';
            }
        }
    @endphp

    <form id="update-form-{{ $task->id }}" action="/tasks/{{ $task->id }}" method="POST">
        @csrf
        @method('PUT')
        <label>Task Name</label>
        <input type="text" name="name" value="{{ $task->name }}">

        <label>Description</label>
        <textarea name="description">{{ $task->description }}</textarea>

        <!-- Dynamic Tags Display -->
        @if ($task->tags && $task->tags->count())
            <div style="margin-top: 8px; display: flex; gap: 6px; flex-wrap: wrap;">
                @foreach ($task->tags as $tag)
                    <span style="background: #e0f2e9; color: #166534; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        <label>Date</label>
        <input type="date" name="date" value="{{ $task->date }}">

        <label>Category</label>
        <select name="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <label>Repeat</label>
        <select name="repeat_type" id="repeat_type_{{ $task->id }}" onchange="toggleCustomHours_{{ $task->id }}()">
            <option value="none" {{ $task->repeat_type == 'none' ? 'selected' : '' }}>Does not repeat</option>
            <option value="daily" {{ $task->repeat_type == 'daily' ? 'selected' : '' }}>Daily</option>
            <option value="weekly" {{ $task->repeat_type == 'weekly' ? 'selected' : '' }}>Weekly</option>
            <option value="custom" {{ $task->repeat_type == 'custom' ? 'selected' : '' }}>Custom (hours)</option>
        </select>

        <div id="customHoursDiv_{{ $task->id }}" style="{{ ($task->repeat_type ?? request('repeat_type')) == 'custom' ? 'display:block;' : 'display:none;' }} margin-top: 10px; padding: 12px; background: #f0fdf4; border-radius: 10px; border: 1px solid #86efac;">
            <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; color: #166534;">
                🔁 Repeat every
                <input
                    type="number"
                    name="repeat_interval_value"
                    min="1"
                    value="{{ $repeatValue ?? 1 }}"
                    style="width: 70px; padding: 6px 10px; border-radius: 8px; border: 2px solid #86efac; font-weight: bold; text-align: center;"
                >
                <select name="repeat_interval_unit" style="padding: 6px 10px; border-radius: 8px; border: 2px solid #86efac; font-weight: bold;">
                    <option value="minutes" {{ ($repeatUnit ?? '') == 'minutes' ? 'selected' : '' }}>Minutes</option>
                    <option value="hours" {{ ($repeatUnit ?? '') == 'hours' ? 'selected' : '' }}>Hours</option>
                </select>
            </label>
        </div>

        <script>
            function toggleCustomHours_{{ $task->id }}() {
                const select = document.getElementById('repeat_type_{{ $task->id }}');
                const div = document.getElementById('customHoursDiv_{{ $task->id }}');
                div.style.display = select.value === 'custom' ? 'block' : 'none';
            }
        </script>

        <br>
        <div style="border-left: 6px solid {{ $task->priority == 'high' ? '#dc2626' : ($task->priority == 'medium' ? '#f59e0b' : '#22c55e') }}; padding-left: 12px; margin: 15px 0;">
            <label>Priority</label>
            <select name="priority">
                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>
    </form>

    <div class="checkbox">
        <form action="/tasks/{{ $task->id }}/toggle" method="POST" id="toggle-form-{{ $task->id }}">
            @csrf
            @method('PATCH')
            <input
                type="checkbox"
                name="completed"
                id="completed_{{ $task->id }}"
                value="1"
                {{ $task->completed ? 'checked' : '' }}
                onchange="document.getElementById('toggle-form-{{ $task->id }}').submit();"
            >
            <label for="completed_{{ $task->id }}">Completed</label>
        </form>
    </div>

    <!-- Actions row -->
    <div class="actions">
        <button type="submit" form="update-form-{{ $task->id }}" class="update-btn">Update Task</button>

        <form action="/tasks/{{ $task->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Delete</button>
        </form>
    </div>
</div>
