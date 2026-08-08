@extends('layouts.app')

@section('title', 'Dashboard - Task Manager')

@section('content')

    <div class="hero-panel">
        <div class="page-header">
            <div class="icon-box">🏠</div>
        </div>
        <h1>Dashboard</h1>
        <p class="subtitle">Here's what's on your plate today</p>
    </div>

    <div class="task-section">

        <h2><span class="section-badge badge-pending">📌</span> Today's Tasks</h2>

        @if($todaysTasks->isEmpty())
            <p class="empty-msg">No tasks due today.</p>
        @endif

        @foreach($todaysTasks as $task)
            <div class="task-card" style="padding: 16px 22px;">
                <h3 style="font-family: var(--font-display); color: var(--color-ink); font-size: 17px; font-weight: 600;">
                    {{ $task->name }}
                </h3>
            </div>
        @endforeach

    </div>

    <a href="/tasks" class="add-btn">View All Tasks</a>

@endsection
