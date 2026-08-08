<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

            --font-display: 'Playfair Display', serif;
            --font-body: 'Poppins', sans-serif;

            --radius-lg: 26px;
            --radius-md: 14px;
            --radius-sm: 9px;

            --shadow-lifted: 0 20px 50px rgba(31, 92, 55, 0.14), 0 4px 14px rgba(31, 92, 55, 0.08);
        }

        body {
            font-family: var(--font-body);
            color: var(--color-ink);
            background: var(--color-page-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 32px 20px;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 620px;
        }

        .auth-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 24px;
        }

        .auth-logo a {
            display: block;
        }

        .auth-logo svg,
        .auth-logo img {
            width: 56px;
            height: 56px;
            color: var(--color-navy);
            fill: var(--color-navy);
        }

        .auth-card {
            background: var(--color-surface-alt);
            border: 1px solid var(--color-border-soft);
            border-radius: var(--radius-lg);
            padding: 52px 48px;
            box-shadow: var(--shadow-lifted);
        }

        .auth-card .field-label {
            display: block;
            margin-top: 18px;
            margin-bottom: 8px;
            color: var(--color-ink);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .auth-card .field-label:first-of-type {
            margin-top: 0;
        }

        .auth-card .field-input {
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid var(--color-border);
            border-radius: var(--radius-sm);
            background: var(--color-surface-sunken);
            font-family: var(--font-body);
            font-size: 15px;
            color: var(--color-ink);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .auth-card .field-input:focus {
            outline: none;
            border-color: var(--color-primary);
            background: var(--color-surface-alt);
            box-shadow: 0 0 0 4px var(--color-accent-soft);
        }

        .auth-card .field-error {
            list-style: none;
            color: var(--color-danger-dark);
            font-size: 13px;
            margin-top: 6px;
            padding-left: 0;
        }

        .auth-card .field-error li {
            margin-top: 2px;
        }

        .auth-status {
            background: var(--color-accent-soft);
            color: var(--color-primary-dark);
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            border: 1px solid rgba(63, 174, 92, 0.25);
        }

        .auth-card a.underline {
            color: var(--color-navy) !important;
            text-decoration: none !important;
            border-bottom: 1px solid var(--color-border);
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease, border-color 0.2s ease;
        }

        .auth-card a.underline:hover {
            color: var(--color-primary) !important;
            border-color: var(--color-primary);
        }

        .main-btn {
            display: inline-block;
            border: none;
            border-radius: var(--radius-sm);
            padding: 12px 26px;
            font-family: var(--font-body);
            font-weight: 600;
            font-size: 14.5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
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

        .auth-card input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--color-primary);
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        {{ $slot }}
    </div>
</div>
</body>
</html>
