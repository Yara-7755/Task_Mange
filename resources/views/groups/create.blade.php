@extends('layouts.app')

@section('title', 'Create Group - Task Manager')

@section('styles')
    .form-container {
    max-width: 600px;
    margin: 40px auto;
    background: var(--color-surface, #ffffff);
    border: 1px solid var(--color-border-soft, #e2e8f0);
    border-radius: var(--radius-md, 12px);
    padding: 32px;
    box-shadow: var(--shadow-soft, 0 4px 6px -1px rgba(0, 0, 0, 0.05));
    }

    .form-header {
    text-align: center;
    margin-bottom: 28px;
    }

    .form-header h1 {
    font-family: var(--font-display);
    font-size: 26px;
    font-weight: 700;
    color: var(--color-ink, #1f2937);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    }

    .form-header p {
    color: var(--color-ink-muted, #6b7280);
    font-size: 14px;
    margin-top: 6px;
    }

    .form-group {
    margin-bottom: 24px;
    text-align: left;
    }

    .form-label {
    display: block;
    font-weight: 600;
    font-size: 14px;
    color: var(--color-ink, #374151);
    margin-bottom: 8px;
    }

    .form-input {
    width: 100%;
    padding: 12px 16px;
    background: var(--color-surface-sunken, #f9fafb);
    border: 1px solid var(--color-border-soft, #d1d5db);
    border-radius: var(--radius-sm, 8px);
    font-size: 14.5px;
    color: var(--color-ink, #111827);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
    outline: none;
    }

    .form-input:focus {
    border-color: var(--color-primary, #047857);
    box-shadow: 0 0 0 3px rgba(4, 120, 87, 0.15);
    background: #ffffff;
    }

    .form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 32px;
    }

    .btn-submit {
    background: #1e3a2b; /* اللون الأخضر الغامق المطابق للهيدر والداشبورد */
    color: #ffffff;
    padding: 10px 24px;
    border-radius: var(--radius-sm, 8px);
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.1s ease;
    }

    .btn-submit:hover {
    background: #14281e;
    transform: translateY(-1px);
    }

    .btn-cancel {
    color: var(--color-ink-muted, #6b7280);
    text-decoration: none;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 500;
    border-radius: var(--radius-sm, 8px);
    transition: background 0.2s ease;
    }

    .btn-cancel:hover {
    background: var(--color-surface-sunken, #f3f4f6);
    color: var(--color-ink, #111827);
    }
@endsection

@section('content')
    <div class="form-container">
        <div class="form-header">
            <h1>👥 Create New Group</h1>
            <p>Set up a new team space to collaborate on tasks</p>
        </div>

        <form action="{{ route('groups.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Group Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="e.g. Study Team, Work Project"
                    required
                    autofocus
                >
            </div>

            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit">Create Group</button>
            </div>
        </form>
    </div>
@endsection
