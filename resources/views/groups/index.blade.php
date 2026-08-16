@extends('layouts.app')

@section('title', 'My Groups - Task Manager')

@section('styles')
    .groups-page {
    max-width: 1200px;
    margin: 0 auto;
    }

    /* =========================
    Hero Header
    ========================= */
    .groups-header-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
    }

    .groups-title-box h1 {
    font-family: var(--font-display);
    font-size: 28px;
    font-weight: 700;
    color: var(--color-ink);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    }

    .groups-title-box p {
    color: var(--color-ink-muted);
    font-size: 14px;
    margin-top: 4px;
    }

    .btn-create-group {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #1e3a2b;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: var(--radius-sm, 8px);
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: background 0.2s ease, transform 0.1s ease;
    box-shadow: var(--shadow-soft);
    }

    .btn-create-group:hover {
    background: #14281e;
    transform: translateY(-1px);
    }

    /* =========================
    Alert Success
    ========================= */
    .alert-success {
    background: #e6f4ea;
    border: 1px solid #b7e1cd;
    color: #137333;
    padding: 12px 16px;
    border-radius: var(--radius-sm, 8px);
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
    }

    /* =========================
    Groups Grid & Cards
    ========================= */
    .groups-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    }

    .group-card {
    background: var(--color-surface, #ffffff);
    border: 1px solid var(--color-border-soft, #e2e8f0);
    border-radius: var(--radius-md, 12px);
    padding: 20px;
    box-shadow: var(--shadow-soft, 0 4px 6px -1px rgba(0, 0, 0, 0.05));
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    }

    .group-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-card-hover, 0 10px 15px -3px rgba(0, 0, 0, 0.1));
    }

    .group-card-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 12px;
    }

    .group-name {
    font-family: var(--font-display);
    font-size: 18px;
    font-weight: 700;
    color: var(--color-ink);
    margin: 0;
    }

    .badge-role {
    background: var(--color-accent-soft, #fef3c7);
    color: var(--color-primary-dark, #92400e);
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    }

    .group-card-body {
    margin-bottom: 20px;
    }

    .group-meta {
    font-size: 13.5px;
    color: var(--color-ink-muted, #6b7280);
    display: flex;
    align-items: center;
    gap: 6px;
    }

    .group-card-footer {
    border-top: 1px solid var(--color-border-soft, #f3f4f6);
    padding-top: 14px;
    text-align: right;
    }

    .link-details {
    color: var(--color-primary, #047857);
    text-decoration: none;
    font-weight: 600;
    font-size: 13.5px;
    transition: color 0.2s ease;
    }

    .link-details:hover {
    color: #1e3a2b;
    text-decoration: underline;
    }

    /* Empty State */
    .empty-groups {
    background: var(--color-surface, #ffffff);
    border: 1px dashed var(--color-border-soft, #d1d5db);
    border-radius: var(--radius-md, 12px);
    padding: 40px;
    text-align: center;
    color: var(--color-ink-muted, #6b7280);
    }

    @media (max-width: 640px) {
    .groups-grid {
    grid-template-columns: 1fr;
    }
    }
@endsection

@section('content')
    <div class="groups-page">

        {{-- Top Header Section --}}
        <div class="groups-header-wrap">
            <div class="groups-title-box">
                <h1>👥 My Groups</h1>
                <p>Manage and collaborate with your team spaces</p>
            </div>

            <a href="{{ route('groups.create') }}" class="btn-create-group">
                <span>+</span> Create New Group
            </a>
        </div>

        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="alert-success">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Groups List Grid --}}
        @if (isset($groups) && $groups->count() > 0)
            <div class="groups-grid">
                @foreach ($groups as $group)
                    <div class="group-card">
                        <div>
                            <div class="group-card-header">
                                <h3 class="group-name">{{ $group->name }}</h3>

                                {{-- إذا كان المستخدم هو الأدمن --}}
                                @if (isset($group->is_admin) && $group->is_admin)
                                    <span class="badge-role">👑 Admin</span>
                                @endif
                            </div>

                            <div class="group-card-body">
                                <div class="group-meta">
                                    <span>👤 Members:</span>
                                    <strong>{{ $group->members_count ?? $group->users_count ?? 1 }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="group-card-footer">
                            <a href="{{ route('groups.show', $group->id) }}" class="link-details">
                                View Details & Members →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-groups">
                <p>You haven't joined or created any groups yet.</p>
                <a href="{{ route('groups.create') }}" class="link-details" style="display: inline-block; margin-top: 8px;">
                    + Create your first group
                </a>
            </div>
        @endif

    </div>
@endsection
