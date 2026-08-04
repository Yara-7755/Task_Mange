<div class="task-card">

    <div class="task-header">
        <h3>{{ $task->name }}</h3>

        @if($task->priority == 'high')
            <span class="priority high">High</span>
        @elseif($task->priority == 'medium')
            <span class="priority medium">Medium</span>
        @else
            <span class="priority low">Low</span>
        @endif
    </div>


    <p class="description">
        {{ $task->description }}
    </p>


    <div class="task-info">
        <span>📅 {{ $task->date }}</span>
        <span>📁 {{ $task->category->name ?? 'No Category' }}</span>
    </div>


    <div class="actions">

        <!-- Complete Button -->
        <form action="/tasks/{{ $task->id }}/toggle" method="POST">
            @csrf

            <button type="submit" class="complete-btn">
                @if($task->completed)
                    Undo
                @else
                    Complete
                @endif
            </button>
        </form>


        <!-- Edit Button -->
        <a href="/tasks/{{ $task->id }}/edit" class="edit-btn">
            Edit
        </a>


        <!-- Delete Button -->
        <form action="/tasks/{{ $task->id }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="delete-btn">
                Delete
            </button>
        </form>

    </div>

</div>
