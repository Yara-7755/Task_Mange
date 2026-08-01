<div class="task-card">
    <!-- Update Form -->
    <form id="update-form-{{ $task->id }}" action="/tasks/{{ $task->id }}" method="POST">
        @csrf
        @method('PUT')
        <label>Task Name</label>
        <input type="text" name="name" value="{{ $task->name }}">
        <label>Description</label>
        <textarea name="description">{{ $task->description }}</textarea>
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
        <div class="checkbox">
            <input type="checkbox" name="completed" value="1" {{ $task->completed ? 'checked' : '' }}>
            <label>Completed</label>
        </div>
    </form>

    <!-- Actions row: Update button (linked to the form above via form="") + Delete form, side by side -->
    <div class="actions">
        <button type="submit" form="update-form-{{ $task->id }}" class="update-btn">Update Task</button>

        <form action="/tasks/{{ $task->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">Delete</button>
        </form>
    </div>
</div>
