<div class="task-card">

    <div class="task-header">
        <h3>{{ $task->name }}</h3>

        @if ($task->priority == 'high')
            <span class="priority high">High</span>
        @elseif ($task->priority == 'medium')
            <span class="priority medium">Medium</span>
        @else
            <span class="priority low">Low</span>
        @endif
    </div>

    <p class="description">{{ $task->description }}</p>

    <div class="task-info">
        <span>📅 {{ $task->date ?? 'No date' }}</span>
        <span>📁 {{ $task->category->name ?? 'No Category' }}</span>
    </div>

    @if ($task->tags->count())
        <div style="margin: -8px 0 16px; display: flex; gap: 6px; flex-wrap: wrap;">
            @foreach ($task->tags as $tag)
                <span class="tag-chip">#{{ $tag->name }}</span>
            @endforeach
        </div>
    @endif

    <div class="checkbox">
        <form action="/tasks/{{ $task->id }}/toggle" method="POST" id="toggle-form-{{ $task->id }}">
            @csrf
            @method('PATCH')
            <input
                type="checkbox"
                id="completed_{{ $task->id }}"
                {{ $task->completed ? 'checked' : '' }}
                onchange="document.getElementById('toggle-form-{{ $task->id }}').submit();"
            >
            <label for="completed_{{ $task->id }}">Completed</label>
        </form>
    </div>

    <button type="button" class="details-toggle-btn" onclick="toggleDetails_{{ $task->id }}()" id="detailsToggleBtn_{{ $task->id }}">
        ✏️ Edit Details
    </button>

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

    <div class="task-details" id="details_{{ $task->id }}" style="display:none;">

        <!-- Update form: inline editing, submitted via the button below -->
        <form id="update-form-{{ $task->id }}" action="/tasks/{{ $task->id }}" method="POST">
            @csrf
            @method('PUT')

            <label>Task Name</label>
            <input type="text" name="name" value="{{ $task->name }}">

            <label>Description</label>
            <textarea name="description">{{ $task->description }}</textarea>

            <label>Tags (comma separated)</label>
            <input type="text" name="tags" value="{{ $task->tags->pluck('name')->join(', ') }}">

            <div class="form-row">
                <div>
                    <label>Date</label>
                    <input type="date" name="date" value="{{ $task->date }}">
                </div>

                <div>
                    <label>Category</label>
                    <select name="category_id">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $task->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <label>Repeat</label>
            <select name="repeat_type" id="repeat_type_{{ $task->id }}" onchange="toggleCustomHours_{{ $task->id }}()">
                <option value="none" {{ $task->repeat_type == 'none' ? 'selected' : '' }}>Does not repeat</option>
                <option value="daily" {{ $task->repeat_type == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ $task->repeat_type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="custom" {{ $task->repeat_type == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>

            <div id="customHoursDiv_{{ $task->id }}" style="{{ $task->repeat_type == 'custom' ? 'display:block;' : 'display:none;' }} margin-top: 14px; padding: 16px; background: var(--color-surface-sunken); border-radius: var(--radius-sm); border: 1px solid var(--color-border-soft);">
                <label style="display: flex; align-items: center; gap: 10px; margin-top: 0;">
                    🔁 Repeat every
                    <input
                        type="number"
                        name="repeat_interval_value"
                        min="1"
                        value="{{ $repeatValue ?? 1 }}"
                        style="width: 80px; text-align: center;"
                    >
                    <select name="repeat_interval_unit" style="width: auto;">
                        <option value="minutes" {{ $repeatUnit == 'minutes' ? 'selected' : '' }}>Minutes</option>
                        <option value="hours" {{ $repeatUnit == 'hours' ? 'selected' : '' }}>Hours</option>
                    </select>
                </label>
            </div>

            <label>Priority</label>
            <select name="priority">
                <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </form>

        <div class="actions">
            <button type="submit" form="update-form-{{ $task->id }}" class="update-btn">Update Task</button>
        </div>

    </div>

    <div class="actions">
        <form action="/tasks/{{ $task->id }}/toggle" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="complete-btn">
                {{ $task->completed ? 'Undo' : 'Complete' }}
            </button>
        </form>

        <form action="/tasks/{{ $task->id }}" method="POST" onsubmit="return confirm('Delete this task permanently?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Delete</button>
        </form>
    </div>

    <script>
        function toggleCustomHours_{{ $task->id }}() {
            const select = document.getElementById('repeat_type_{{ $task->id }}');
            const div = document.getElementById('customHoursDiv_{{ $task->id }}');
            div.style.display = select.value === 'custom' ? 'block' : 'none';
        }

        function toggleDetails_{{ $task->id }}() {
            const details = document.getElementById('details_{{ $task->id }}');
            const btn = document.getElementById('detailsToggleBtn_{{ $task->id }}');
            const isOpen = details.style.display === 'block';
            details.style.display = isOpen ? 'none' : 'block';
            btn.textContent = isOpen ? '✏️ Edit Details' : '✕ Close Details';
        }
    </script>

</div>
