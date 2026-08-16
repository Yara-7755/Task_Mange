
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Task Manager')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <style>

        /* ============================================================
           Reset
        ============================================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        [x-cloak] {
            display: none !important;
        }

        /* ============================================================
           Design tokens
        ============================================================ */
        :root {
            --color-page-bg: #e6ddc2;
            --color-surface: #f1ecda;
            --color-surface-alt: #ffffff;
            --color-surface-sunken: #f3efe2;

            --color-navy: #2d4359;
            --color-navy-dark: #1c2739;

            --color-ink: #1a2a20;
            --color-ink-muted: #6f7d73;
            --color-ink-soft: #93a099;

            --color-primary: #1f5c37;
            --color-primary-dark: #123c22;
            --color-accent: #3fae5c;
            --color-accent-soft: rgba(63, 174, 92, 0.14);

            --color-border: #e4ddc9;
            --color-border-soft: #ece6d5;

            --color-danger: #b3413c;
            --color-danger-soft: rgba(179, 65, 60, 0.1);
            --color-danger-dark: #8c2f2b;

            --color-warning: #c08a2e;
            --color-warning-soft: rgba(192, 138, 46, 0.14);

            --font-display: 'Playfair Display', serif;
            --font-body: 'Poppins', sans-serif;
            --font-mono: 'Courier New', ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;

            --radius-lg: 26px;
            --radius-md: 14px;
            --radius-sm: 9px;

            --shadow-soft: 0 1px 2px rgba(18, 38, 26, 0.04), 0 8px 20px rgba(18, 38, 26, 0.06);
            --shadow-lifted: 0 20px 50px rgba(31, 92, 55, 0.14), 0 4px 14px rgba(31, 92, 55, 0.08);
            --shadow-card-hover: 0 14px 32px rgba(18, 38, 26, 0.1);
        }

        html[data-theme="dark"] {
            --color-page-bg: #12161b;
            --color-surface: #1b2129;
            --color-surface-alt: #232a34;
            --color-surface-sunken: #1e2530;

            --color-navy: #3a5774;
            --color-navy-dark: #24344a;

            --color-ink: #e9e7dd;
            --color-ink-muted: #a3ac9f;
            --color-ink-soft: #7c8a80;

            --color-primary: #3fae5c;
            --color-primary-dark: #2c7d43;
            --color-accent: #4fc26c;
            --color-accent-soft: rgba(79, 194, 108, 0.16);

            --color-border: #323b46;
            --color-border-soft: #2a323c;

            --color-danger: #d6605a;
            --color-danger-soft: rgba(214, 96, 90, 0.14);
            --color-danger-dark: #e08883;

            --color-warning: #e0ab52;
            --color-warning-soft: rgba(224, 171, 82, 0.16);

            --shadow-lifted: 0 20px 50px rgba(0, 0, 0, 0.45), 0 4px 14px rgba(0, 0, 0, 0.3);
            --shadow-soft: 0 1px 2px rgba(0, 0, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.25);
            --shadow-card-hover: 0 14px 32px rgba(0, 0, 0, 0.35);
        }

        /* the two elements below use a hard-coded #ffffff instead of a
           variable, so they need explicit dark over rides */
        html[data-theme="dark"] .top-nav,
        html[data-theme="dark"] .notification-dropdown {
            background: var(--color-surface-alt);
        }

        body, .container, .top-nav, .notification-dropdown, input, textarea, select, button, a {
            transition: background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .theme-toggle {
            position: fixed;
            top: 22px;
            right: 22px;
            z-index: 200;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid var(--color-border);
            background: var(--color-surface-alt);
            color: var(--color-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            cursor: pointer;
            box-shadow: var(--shadow-soft);
        }

        .theme-toggle:hover {
            transform: translateY(-2px);
        }

        body {
            font-family: var(--font-body);
            color: var(--color-ink);
            background: var(--color-page-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 24px 64px;
            position: relative;
            overflow-x: hidden;
        }

        /* ============================================================
           Navigation Bar Styles
        ============================================================ */
        .top-nav {
            width: 96%;
            max-width: 1800px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 28px;
            margin-bottom: 24px;
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid var(--color-border-soft);
            box-shadow: var(--shadow-soft);
        }

        .nav-links {
            display: flex;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--color-navy);
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--color-primary);
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-name {
            font-weight: 600;
            color: var(--color-navy);
            font-size: 15px;
        }

        .logout-btn {
            background: var(--color-danger);
            color: #fff;
            padding: 8px 18px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
        }

        .logout-btn:hover {
            background: var(--color-danger-dark);
        }

        /* Notifications Dropdown */
        .notification-wrapper {
            position: relative;
            display: inline-block;
        }

        .notification-btn {
            background: #c08a2e;
            border: none;
            border-radius: 10px;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            position: relative;
            padding: 0;
            color: #ffffff;
            transition: transform 0.2s ease, background 0.2s ease;
        }

        .notification-btn:hover {
            transform: translateY(-1px);
            background: #a97824;
        }

        .notification-count {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--color-danger);
            color: white;
            font-size: 11px;
            font-weight: 700;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        }

        .notification-dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            width: 300px;
            background: #ffffff;
            border-radius: var(--radius-md);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            border: 1px solid var(--color-border-soft);
            z-index: 100;
            overflow: hidden;
        }

        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--color-border-soft);
            transition: background 0.2s ease;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item.unread {
            background: var(--color-surface-sunken);
        }

        .notification-item p {
            font-size: 13px;
            color: var(--color-ink);
            margin-bottom: 4px;
        }

        .notification-item small {
            font-size: 11px;
            color: var(--color-ink-soft);
        }

        .notification-empty {
            padding: 20px;
            text-align: center;
            color: var(--color-ink-soft);
            font-size: 13px;
        }

        /* ============================================================
           Container Main Layout
        ============================================================ */
        .container {
            position: relative;
            z-index: 1;
            width: 96%;
            max-width: 1800px;
            margin: auto;
            background: var(--color-surface);
            padding: 48px 56px 56px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lifted);
            border: 1px solid var(--color-border);
        }

        @media (max-width: 720px) {
            .container {
                padding: 36px 22px 44px;
            }

            .top-nav {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* ============================================================
           Shared page components (hero, forms, cards, buttons, grid)
        ============================================================ */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 4px;
        }

        .hero-panel {
            background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
            border-radius: var(--radius-lg);
            padding: 40px 32px 34px;
            margin-bottom: 32px;
            box-shadow: 0 14px 30px rgba(16, 25, 34, 0.28);
        }

        .icon-box {
            font-size: 36px;
            line-height: 1;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.2));
        }

        .hero-panel h1 {
            color: #ffffff;
        }

        .hero-panel .subtitle {
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 0;
        }

        h1 {
            font-family: var(--font-display);
            color: var(--color-primary-dark);
            font-size: 40px;
            font-weight: 600;
            text-align: center;
            letter-spacing: -0.01em;
        }

        .subtitle {
            text-align: center;
            color: var(--color-ink-muted);
            margin-bottom: 40px;
            font-size: 15px;
            font-style: italic;
            font-family: var(--font-display);
        }

        h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-display);
            color: var(--color-ink);
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .section-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .badge-pending { background: var(--color-accent-soft); color: var(--color-primary-dark); }
        .badge-expired { background: var(--color-danger-soft); color: var(--color-danger-dark); }
        .badge-completed { background: rgba(63, 174, 92, 0.18); color: var(--color-primary-dark); }

        .success {
            background: var(--color-accent-soft);
            color: var(--color-primary-dark);
            padding: 16px 18px;
            border-radius: var(--radius-md);
            text-align: center;
            margin-bottom: 28px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid rgba(63, 174, 92, 0.25);
        }

        .error-box {
            background: var(--color-danger-soft);
            color: var(--color-danger-dark);
            padding: 16px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid rgba(179, 65, 60, 0.25);
        }

        .error {
            color: var(--color-danger-dark);
            font-size: 13px;
            margin-top: 6px;
        }

        .progress-section {
            margin-bottom: 44px;
            padding: 22px 26px;
            background: var(--color-surface-sunken);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-border-soft);
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 12px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-ink-muted);
        }

        .progress-label strong {
            color: var(--color-primary-dark);
            font-size: 14px;
            font-family: var(--font-body);
            letter-spacing: 0;
            text-transform: none;
        }

        .progress-bar-bg {
            width: 100%;
            height: 10px;
            background: var(--color-border);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
            border-radius: 999px;
            transition: width 0.4s ease;
        }

        .empty-msg {
            text-align: center;
            color: var(--color-ink-soft);
            padding: 22px;
            font-size: 14px;
            font-style: italic;
            font-family: var(--font-display);
        }

        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            align-items: start;
            margin-bottom: 32px;
        }

        .task-section {
            background: var(--color-surface-alt);
            border: 1px solid var(--color-border-soft);
            border-radius: var(--radius-lg);
            padding: 28px 24px 12px;
            margin-bottom: 0;
            box-shadow: var(--shadow-soft);
        }

        .task-section h2 {
            padding-bottom: 20px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--color-border-soft);
            justify-content: center;
            text-align: center;
        }

        .pending-section { border-top: 3px solid var(--color-accent); }
        .expired-section { border-top: 3px solid var(--color-danger); }
        .completed-section { border-top: 3px solid var(--color-primary); }

        .task-card {
            background: var(--color-surface-sunken);
            border: 1px solid var(--color-border-soft);
            border-radius: var(--radius-md);
            padding: 22px;
            margin-bottom: 20px;
            transition: box-shadow 0.25s ease, transform 0.25s ease;
            position: relative;
        }

        .task-card:hover {
            box-shadow: var(--shadow-card-hover);
            transform: translateY(-2px);
        }

        .task-card label {
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            color: var(--color-ink);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        input, textarea, select {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--color-border);
            border-radius: var(--radius-sm);
            background: var(--color-surface-alt);
            font-family: var(--font-body);
            font-size: 15px;
            color: var(--color-ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        textarea {
            height: 120px;
            resize: none;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--color-primary);
            background: var(--color-surface-alt);
            box-shadow: 0 0 0 4px var(--color-accent-soft);
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 18px;
        }

        .checkbox input {
            width: 18px;
            height: 18px;
            accent-color: var(--color-primary);
        }

        .checkbox label {
            margin: 0;
            text-transform: none;
            letter-spacing: 0;
            font-size: 14px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 24px;
            align-items: center;
        }

        .actions form {
            margin: 0;
        }

        button {
            padding: 13px 22px;
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            color: #fff;
            font-family: var(--font-body);
            font-size: 14.5px;
            font-weight: 600;
            letter-spacing: 0.01em;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .update-btn {
            order: 1;
            background: linear-gradient(135deg, var(--color-navy), var(--color-navy-dark));
        }

        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(28, 39, 57, 0.32);
        }

        .add-btn {
            display: block;
            text-align: center;
            margin-top: 12px;
            padding: 17px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
            color: #fff;
            text-decoration: none;
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .add-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(16, 25, 34, 0.32);
        }

        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 14px;
        }

        .btn-secondary {
            background: transparent;
            border: 1.5px solid var(--color-navy);
            color: var(--color-navy);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 14.5px;
        }

        .btn-secondary:hover {
            background: var(--color-navy);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-ghost {
            background: transparent;
            border: 1.5px solid var(--color-danger);
            color: var(--color-danger-dark);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;
        }

        .btn-ghost:hover {
            background: var(--color-danger);
            color: #fff;
            transform: translateY(-2px);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 560px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .task-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .task-header h3 {
            font-family: var(--font-display);
            color: var(--color-ink);
            font-size: 19px;
            font-weight: 600;
        }

        .priority {
            padding: 5px 14px;
            border-radius: 999px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            flex-shrink: 0;
        }

        .priority.high { background: var(--color-danger); }
        .priority.medium { background: var(--color-warning); }
        .priority.low { background: var(--color-accent); }

        .description {
            margin: 16px 0;
            color: var(--color-ink-muted);
            line-height: 1.65;
            font-size: 14.5px;
        }

        .task-info {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            color: var(--color-ink-soft);
            font-size: 12.5px;
            font-family: var(--font-mono);
            letter-spacing: 0.02em;
            margin-bottom: 20px;
        }

        .complete-btn, .edit-btn, .delete-btn {
            background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
        }

        .complete-btn:hover, .edit-btn:hover, .delete-btn:hover {
            box-shadow: 0 10px 20px rgba(28, 39, 57, 0.32);
        }

        .actions .edit-btn {
            display: inline-block;
            padding: 13px 22px;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            color: #fff;
            font-family: var(--font-body);
            font-size: 14.5px;
            font-weight: 600;
            letter-spacing: 0.01em;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .actions .edit-btn:hover {
            transform: translateY(-2px);
        }

        .form-card {
            width: 100%;
        }

        .btn-row {
            display: flex;
            gap: 14px;
            margin-top: 24px;
        }

        .btn-row .main-btn {
            flex: 1;
            margin-top: 0;
        }

        @media (max-width: 640px) {
            .btn-row {
                flex-direction: column;
            }
        }

        .main-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 13px 22px;
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 14.5px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            margin-top: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .main-dark {
            background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
            color: #fff;
        }

        .main-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(28, 39, 57, 0.32);
        }

        .main-light {
            background: transparent;
            border: 1.5px solid var(--color-navy);
            color: var(--color-navy);
        }

        .main-light:hover {
            background: var(--color-navy);
            color: #fff;
            transform: translateY(-2px);
        }

        .main-danger {
            background: transparent;
            border: 1.5px solid var(--color-danger);
            color: var(--color-danger-dark);
        }

        .main-danger:hover {
            background: var(--color-danger);
            color: #fff;
            transform: translateY(-2px);
        }

        @media (max-width: 980px) {
            .tasks-grid {
                grid-template-columns: 1fr;
            }
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 28px;
        }

        .search-form input[type="text"] {
            flex: 2;
            min-width: 200px;
        }

        .search-form select {
            flex: 1;
            min-width: 160px;
        }

        .search-form button {
            background: linear-gradient(135deg, var(--color-navy) 0%, var(--color-navy-dark) 100%);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            padding: 13px 22px;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 14.5px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .search-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(28, 39, 57, 0.32);
        }

        .details-toggle-btn {
            display: block;
            width: 100%;
            background: transparent;
            border: 1.5px solid var(--color-navy);
            color: var(--color-navy);
            padding: 10px 18px;
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            margin-top: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        }

        .details-toggle-btn:hover {
            background: var(--color-navy);
            color: #fff;
            transform: translateY(-2px);
        }

        .task-details {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px dashed var(--color-border);
        }

        @yield('styles')
    </style>
    <script>
        (function () {
            var saved = localStorage.getItem('theme');
            if (saved === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body>

<button class="theme-toggle" id="themeToggle" type="button" aria-label="Toggle dark mode">🌙</button>

<!-- Top Navigation Bar -->
<!-- Top Navigation Bar -->
<nav class="top-nav">
    <div class="nav-links">
        <a href="/dashboard">Dashboard</a>
        <a href="/tasks">My Tasks</a>
        <a href="/groups">Groups</a>
    </div>

    <div class="nav-user">
        <!-- Notifications -->
        <div class="notification-wrapper" x-data="{ open: false }">
            <button
                type="button"
                class="notification-btn"
                @click="open = !open"
                @click.away="open = false">
                🔔
                @if(Auth::user()->unreadNotifications->count() > 0)
                    <span class="notification-count">
                        {{ Auth::user()->unreadNotifications->count() }}
                    </span>
                @endif
            </button>

            <div
                x-show="open"
                x-cloak
                class="notification-dropdown">
                @forelse(Auth::user()->notifications->take(10) as $notification)
                    <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                        <p>
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </p>
                        <small>
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>
                @empty
                    <div class="notification-empty">
                        No notifications yet
                    </div>
                @endforelse
            </div>
        </div>

        <!-- User Name -->
        <span class="user-name">
            {{ Auth::user()->name ?? '' }}
        </span>

        <!-- Logout -->
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>
    </div>
</nav>
<!-- Main Content Container (سيظهر فقط إن وُجد محتوى ملموس) -->
@if(trim($__env->yieldContent('content')))
    <div class="container">
        @yield('content')
    </div>
@endif

<script src="//unpkg.com/alpinejs" defer></script>

<script>
    (function () {
        var root = document.documentElement;
        var btn = document.getElementById('themeToggle');

        function updateIcon() {
            btn.textContent = root.getAttribute('data-theme') === 'dark' ? '☀️' : '🌙';
        }

        updateIcon();

        btn.addEventListener('click', function () {
            var isDark = root.getAttribute('data-theme') === 'dark';
            if (isDark) {
                root.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            } else {
                root.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
            updateIcon();
        });
    })();
</script>

</body>
</html>
