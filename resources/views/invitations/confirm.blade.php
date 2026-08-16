@extends('layouts.app')

@section('title', 'Confirming Invitation - Task Manager')

@section('styles')
    .invite-confirm-page {
    max-width: 480px;
    margin: 80px auto;
    text-align: center;
    background: var(--color-surface, #ffffff);
    border: 1px solid var(--color-border-soft, #e2e8f0);
    border-radius: var(--radius-md, 12px);
    padding: 48px 32px;
    box-shadow: var(--shadow-soft, 0 4px 6px -1px rgba(0, 0, 0, 0.05));
    }

    .invite-confirm-page .icon-box {
    font-size: 40px;
    margin-bottom: 16px;
    }

    .invite-confirm-page h2 {
    font-family: var(--font-display);
    font-size: 22px;
    font-weight: 700;
    color: var(--color-ink, #1f2937);
    margin-bottom: 8px;
    }

    .invite-confirm-page p {
    color: var(--color-ink-muted, #6b7280);
    font-size: 14.5px;
    }

    .invite-confirm-page .spinner {
    margin: 24px auto 0;
    width: 28px;
    height: 28px;
    border: 3px solid var(--color-border-soft, #e5e7eb);
    border-top-color: var(--color-primary, #047857);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
    to { transform: rotate(360deg); }
    }

    .invite-confirm-page .manual-btn {
    display: none;
    margin-top: 20px;
    background: #1e3a2b;
    color: #fff;
    padding: 10px 24px;
    border-radius: var(--radius-sm, 8px);
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    }

    .invite-confirm-page .manual-btn:hover {
    background: #14281e;
    }
@endsection

@section('content')
    <div class="invite-confirm-page">
        <div class="icon-box">👥</div>
        <h2>Joining {{ $invitation->group->name }}...</h2>
        <p>Please wait a moment while we confirm your invitation.</p>

        <div class="spinner"></div>

        <form id="autoAcceptForm" action="{{ route('invitations.accept', $invitation->token) }}" method="POST">
            @csrf
            <noscript>
                <button type="submit" class="manual-btn" style="display: block;">
                    Click here to accept
                </button>
            </noscript>
        </form>
    </div>

    <script>
        document.getElementById('autoAcceptForm').submit();
    </script>
@endsection
