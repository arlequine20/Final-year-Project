<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CollabTrack')</title>

    <!-- PlainAdmin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lineicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

    <style>
        body {
            background-color: #f3f6f9;
            transition: 0.3s ease;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 20px;
            transition: 0.3s ease;
            z-index: 1000;
        }

        .sidebar .logo {
            font-size: 24px;
            font-weight: 700;
            color: #16a34a;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            color: #111827;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #16a34a;
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: rgba(255,255,255,0.96);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            box-shadow: 0 6px 18px rgba(2,6,23,0.06);
            transition: 0.25s ease;
            backdrop-filter: blur(4px);
        }

        .page-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 8px 30px rgba(2,6,23,0.06);
            margin-bottom: 24px;
            transition: 0.25s ease;
            border: 1px solid rgba(15,23,42,0.04);
        }

        .logout-btn {
            border: none;
            background: #dc2626;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 500;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        ul.module-list {
            padding-left: 20px;
            margin-bottom: 0;
        }

        ul.module-list li {
            margin-bottom: 10px;
        }

        /* FLASH ALERT */
        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 280px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        /* =========================
           GENERAL DARK MODE
        ========================== */
        body.dark-mode {
            background-color: #0b1220;
            color: #e6eef8;
        }

        body.dark-mode .sidebar {
            background: #0f1724;
            border-right: 1px solid #243345;
        }

        body.dark-mode .sidebar a {
            color: #e6eef8;
        }

        body.dark-mode .sidebar a:hover,
        body.dark-mode .sidebar a.active {
            background-color: #16a34a;
            color: white;
        }

        body.dark-mode .topbar,
        body.dark-mode .page-card {
            background: #0f1724;
            color: #e6eef8;
            box-shadow: 0 8px 30px rgba(0,0,0,0.45);
            border-color: rgba(255,255,255,0.03);
        }

        body.dark-mode .text-muted {
            color: #9aa6b2 !important;
        }

        body.dark-mode .alert-success {
            background-color: #14532d;
            color: #dcfce7;
            border: none;
        }

        body.dark-mode .alert-danger {
            background-color: #7f1d1d;
            color: #fee2e2;
            border: none;
        }

        body.dark-mode .alert-info {
            background-color: #1e3a8a;
            color: #dbeafe;
            border: none;
        }

        body.dark-mode input,
        body.dark-mode textarea,
        body.dark-mode select {
            background-color: #0b1220 !important;
            color: #e6eef8 !important;
            border: 1px solid #243345 !important;
        }

        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder {
            color: #748392 !important;
        }

        body.dark-mode label,
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6,
        body.dark-mode p,
        body.dark-mode li,
        body.dark-mode strong,
        body.dark-mode span,
        body.dark-mode small {
            color: #e6eef8 !important;
        }

        /* =========================
           AUTH PAGE FIX (LOGIN / REGISTER)
        ========================== */
        body.dark-mode.auth-page {
            background-color: #071022 !important;
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page .signin-wrapper,
        body.dark-mode.auth-page .signup-wrapper,
        body.dark-mode.auth-page .form-wrapper {
            background: #071022 !important;
            color: #f8fafc !important;
        }

        .auth-footer {
            margin-top: 40px;
            padding: 24px 0;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        body.dark-mode .auth-footer { color: #cbd5e1; }

        .auth-footer a {
            color: #16a34a;
            text-decoration: none;
            margin: 0 8px;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        body.auth-page {
            background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
        }

        .signin-section {
            min-height: calc(100vh - 100px);
            padding: 40px 0;
        }

        .auth-cover-wrapper {
            padding: 40px;
            border-radius: 30px 0 0 30px;
        }

        .auth-cover {
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100%;
        }

        .auth-cover .title h1 {
            font-size: 42px;
            margin-bottom: 16px;
        }

        .signin-wrapper,
        .signup-wrapper {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 30px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 540px;
            padding: 35px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.08);
        }

        .auth-cover-wrapper.bg-primary-100 {
            background: #e7f5ff;
        }

        /* NEW NOTIFICATION STYLE */


        .notif-dropdown{
            width:380px;
            border-radius:15px;
            overflow:hidden;
        }


        .notif-header{

            padding:15px;

            font-weight:700;

            background:#f8fafc;

            border-bottom:1px solid #e5e7eb;

        }

        .notif-item{

            display:flex;

            gap:12px;

            padding:12px 14px;

            border-bottom:1px solid rgba(15,23,42,0.04);

            transition:.18s ease;

        }

        .notif-item:hover{

            background:rgba(37,99,235,0.04);

        }

        .notif-item.unread{

            background:linear-gradient(90deg, rgba(34,197,94,0.06), rgba(37,99,235,0.03));
            border-left:4px solid #16a34a;

        }

        .notif-icon{

            font-size:20px;
            width:36px;
            height:36px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:8px;
            background:rgba(2,6,23,0.03);
            color:#0b1220;

        }


        .notif-content p{

            margin:0;

            font-size:14px;

            font-weight:600;

        }


        .notif-content small{
            color:#64748b;
        }

        body.dark-mode .notif-item.unread{
            background:linear-gradient(90deg, rgba(34,197,94,0.06), rgba(37,99,235,0.03));
            border-left:4px solid #16a34a;
        }

        body.dark-mode .notif-header{
            background:#071022;
            border-bottom:1px solid rgba(255,255,255,0.03);
        }

        /* Notification popup + badge */
        .notif-badge {
            display:inline-flex;
            min-width:22px;
            height:22px;
            padding:0 7px;
            border-radius:999px;
            background:#ef4444;
            color:white;
            font-size:12px;
            font-weight:700;
            text-align:center;
            line-height:22px;
            margin-left:8px;
            align-items:center;
            justify-content:center;
        }

        .notification-btn {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            padding:10px 14px;
            border-radius:12px;
            background: rgba(15,23,42,0.04);
            border: 1px solid rgba(15,23,42,0.08);
            color: #111827;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .notification-btn:hover,
        .notification-btn:focus {
            background: rgba(15,23,42,0.08);
            border-color: rgba(15,23,42,0.16);
            outline: none;
        }

        .notification-icon {
            width:22px;
            height:22px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            border-radius:8px;
            background: rgba(22,163,74,0.12);
            color: #16a34a;
        }

        .notif-time {
            display:block;
            margin-top:6px;
            color:#94a3b8;
            font-size:12px;
            font-weight:500;
        }

        body.dark-mode .notif-time {
            color:#94a3b8 !important;
        }

        body.dark-mode .notification-btn {
            background: rgba(255,255,255,0.04);
            border-color: rgba(255,255,255,0.10);
            color: #e6eef8;
        }

        body.dark-mode .notification-btn:hover,
        body.dark-mode .notification-btn:focus {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.16);
        }

        body.dark-mode .notification-icon {
            background: rgba(22,163,74,0.16);
            color: #22c55e;
        }

        .theme-switch {
            display: inline-flex;
            align-items:center;
            gap:10px;
            cursor:pointer;
            user-select:none;
            color:#111827;
            font-weight:600;
            outline: none;
        }

        .auth-page .signin-wrapper,
        .auth-page .signup-wrapper,
        .auth-page .form-wrapper {
            background: #ffffff;
            color: #0f1724;
        }

        .auth-page .form-wrapper {
            border: 1px solid rgba(15,23,42,0.08);
        }

        .auth-page .signin-option a,
        .auth-page .singin-option a {
            color: #16a34a;
        }

        body.dark-mode.auth-page .signin-wrapper,
        body.dark-mode.auth-page .signup-wrapper,
        body.dark-mode.auth-page .form-wrapper {
            background: #0f1724 !important;
            color: #e6eef8 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }

        body.dark-mode.auth-page .auth-cover-wrapper {
            background: #000000 !important;
            border: 1px solid rgba(255,255,255,0.14) !important;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.55);
        }

        body.dark-mode.auth-page .auth-cover-wrapper.bg-primary-100 {
            background: #000000 !important;
        }

        body.dark-mode.auth-page .auth-cover .title h1,
        body.dark-mode.auth-page .auth-cover .title p,
        body.dark-mode.auth-page .auth-footer,
        body.dark-mode.auth-page .signin-option p,
        body.dark-mode.auth-page .singin-option p {
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page .auth-footer {
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page .auth-cover .title h1 {
            color: #ffffff !important;
        }

        body.dark-mode.auth-page .signin-option a,
        body.dark-mode.auth-page .singin-option a,
        body.dark-mode.auth-page a {
            color: #86efac !important;
        }

        body.dark-mode.auth-page input,
        body.dark-mode.auth-page textarea,
        body.dark-mode.auth-page select {
            background-color: #0b1220 !important;
            color: #e6eef8 !important;
            border: 1px solid #374151 !important;
        }

        body.dark-mode.auth-page input::placeholder,
        body.dark-mode.auth-page textarea::placeholder {
            color: #94a3b8 !important;
        }

        .auth-page .form-wrapper .alert {
            border-radius: 12px;
        }

        .btn-outline-secondary {
            border-color: rgba(15,23,42,0.12) !important;
            color: #0f1724 !important;
        }

        .btn-outline-secondary:hover {
            background: rgba(15,23,42,0.06) !important;
            color: #0f1724 !important;
        }

        body.dark-mode .btn-outline-secondary {
            border-color: rgba(255,255,255,0.18) !important;
            color: #e6eef8 !important;
        }

        body.dark-mode .btn-outline-secondary:hover {
            background: rgba(255,255,255,0.08) !important;
        }

        body.dark-mode .list-group-item {
            background: rgba(255,255,255,0.04) !important;
            color: #e6eef8 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }

        body.dark-mode .list-group-item span {
            color: #f8fafc !important;
        }

        body.dark-mode .page-card h5,
        body.dark-mode .page-card .text-muted,
        body.dark-mode .page-card strong,
        body.dark-mode .page-card .badge {
            color: #e6eef8 !important;
        }

        .d-flex.gap-2.flex-wrap > form {
            display: inline-flex !important;
            align-items: center;
            margin: 0;
        }

        .d-flex.gap-2.flex-wrap > form button,
        .d-flex.gap-2.flex-wrap > form a {
            margin: 0 !important;
            white-space: nowrap;
        }

        .task-card .d-flex.gap-2.flex-wrap > form {
            min-width: auto !important;
        }

        .task-card .d-flex.gap-2.flex-wrap > form button,
        .task-card .d-flex.gap-2.flex-wrap > form a {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            margin: 0 !important;
        }

        .task-card .d-flex.gap-2.flex-wrap > form {
            display: inline-flex !important;
            align-items: center;
            margin: 0 !important;
        }

        .task-card .d-flex.gap-2.flex-wrap > form button.btn-delete-custom,
        .task-card .d-flex.gap-2.flex-wrap > form button.btn-edit-custom,
        .task-card .d-flex.gap-2.flex-wrap > form a.btn-edit-custom {
            white-space: nowrap;
        }

        body.dark-mode .table,
        body.dark-mode .table th,
        body.dark-mode .table td,
        body.dark-mode .table thead th {
            color: #e6eef8 !important;
            border-color: rgba(255,255,255,0.10) !important;
        }

        body.dark-mode .table thead th {
            color: #f8fafc !important;
            background: rgba(255,255,255,0.04) !important;
        }

        body.dark-mode .table tbody tr {
            background: rgba(255,255,255,0.02) !important;
        }

        body.dark-mode .table-hover tbody tr:hover {
            background: rgba(255,255,255,0.06) !important;
        }

        body.dark-mode .page-card .badge {
            opacity: 1 !important;
        }

        body.dark-mode .alert {
            background-color: #0f1724;
            border-color: rgba(255,255,255,0.08);
            color: #e6eef8;
        }

        body.dark-mode .auth-footer {
            color: #cbd5e1;
        }

        body.dark-mode .auth-cover .title h1,
        body.dark-mode .auth-cover .title p {
            color: #e6eef8 !important;
        }

        body.dark-mode.auth-page .auth-footer,
        body.dark-mode.auth-page .auth-cover .title h1 {
            color: #e6eef8 !important;
        }

        body.dark-mode.auth-page .logo,
        body.dark-mode.auth-page .auth-cover .title h1 {
            color: #e6eef8 !important;
        }

        .theme-switch .switch-slider {
            width:44px;
            height:24px;
            background:#e5e7eb;
            border-radius:999px;
            display:inline-block;
            position:relative;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
        }

        .theme-switch .switch-slider::after {
            content:'';
            width:18px;
            height:18px;
            background:white;
            border-radius:50%;
            position:absolute;
            top:3px;
            left:3px;
            transition:all .25s ease;
            box-shadow:0 2px 6px rgba(2,6,23,0.12);
        }

        body.dark-mode .theme-switch .switch-label { color: #f9fafb; }
        body.dark-mode .theme-switch .switch-slider { background:#374151; }
        body.dark-mode .theme-switch .switch-slider::after { left:calc(100% - 21px); }

        .theme-switch .switch-label:focus-visible {
            box-shadow: 0 0 0 4px rgba(37,99,235,0.14);
            border-radius: 8px;
        }

        #notification-popup{
            position:fixed;
            top:25px;
            right:25px;
            background:rgba(11,17,28,0.95);
            color:var(--notif-color, #e6eef8);
            padding:14px 18px;
            border-radius:12px;
            display:none;
            z-index:9999;
            box-shadow:0 8px 24px rgba(2,6,23,0.28);
            font-weight:600;
            max-width:360px;
            border:1px solid rgba(255,255,255,0.02);
        }

        body.dark-mode #notification-popup{ background:#071022; color:#e6eef8; border-color: rgba(255,255,255,0.04); }
        body.dark-mode .notif-badge { background:#ef4444; color:white; }




    </style>
    <style>
        /* Override and polish buttons for consistent, professional look */
        .main-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            font-weight: 600;
            border: 1px solid transparent;
            transition: all .18s ease;
        }

        .main-btn.primary-btn,
        .main-btn.primary-btn.btn-hover,
        .primary-btn {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            color: #ffffff !important;
            box-shadow: 0 8px 24px rgba(22,163,74,0.10);
        }

        .main-btn.primary-btn:hover,
        .primary-btn:hover {
            background-color: #15803d !important;
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(22,163,74,0.14);
        }

        /* Secondary/muted buttons */
        .main-btn.light-btn,
        .muted-btn {
            background: transparent !important;
            border: 1px solid rgba(15,23,42,0.06) !important;
            color: #0b1220 !important;
        }

        /* Custom action buttons */
        .btn-edit-custom,
        .btn-manage-members,
        .btn-reopen {
            display: inline-flex !important;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1rem;
            border-radius: 0.85rem;
            border: 1px solid rgba(15,23,42,0.12);
            background: #f8fafc;
            color: #0f1724;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .btn-edit-custom:hover,
        .btn-manage-members:hover,
        .btn-reopen:hover {
            background: #e5e7eb;
            border-color: rgba(15,23,42,0.18);
            color: #0b1220;
        }

        .btn-reopen {
            color: #166534;
            border-color: rgba(22,163,74,0.24);
            background: rgba(22,163,74,0.08);
        }

        .btn-reopen:hover {
            background: rgba(22,163,74,0.16);
            color: #14532d;
        }

        body.dark-mode .btn-edit-custom,
        body.dark-mode .btn-manage-members {
            background: rgba(255,255,255,0.05);
            border-color: rgba(255,255,255,0.14);
            color: #e6eef8;
        }

        body.dark-mode .btn-edit-custom:hover,
        body.dark-mode .btn-manage-members:hover {
            background: rgba(255,255,255,0.10);
            color: #ffffff;
        }

        body.dark-mode .btn-reopen {
            border-color: rgba(22,163,74,0.30);
            background: rgba(22,163,74,0.14);
            color: #86efac;
        }

        body.dark-mode .btn-reopen:hover {
            background: rgba(22,163,74,0.22);
            color: #4ade80;
        }

        /* Danger buttons */
        .btn-delete-custom,
        .comment-delete-btn {
            background: transparent !important;
            border: 1px solid transparent !important;
            color: #dc2626 !important;
            padding: 0.35rem 0.75rem !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .comment-delete-btn {
            border: none !important;
            padding: 0 !important;
        }

        .comment-delete-btn:hover,
        .btn-delete-custom:hover {
            background: rgba(220,38,38,0.08) !important;
            color: #b91c1c !important;
        }

        .btn-delete-custom.w-100 {
            width: 100%;
        }

        .btn-danger {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            color: #fff !important;
        }

        .btn-danger:hover {
            background-color: #b91c1c !important;
            border-color: #b91c1c !important;
        }

        /* Dark mode tweaks */
        body.dark-mode .main-btn.primary-btn,
        body.dark-mode .primary-btn {
            background-color: #16a34a !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.35) !important;
            color: #ffffff !important;
        }

        body.dark-mode .main-btn.light-btn,
        body.dark-mode .muted-btn,
        body.dark-mode .btn-outline-secondary {
            background: transparent !important;
            border-color: rgba(255,255,255,0.18) !important;
            color: #e6eef8 !important;
        }

        body.dark-mode .btn-outline-secondary:hover {
            background: rgba(255,255,255,0.08) !important;
            color: #ffffff !important;
        }

        body.dark-mode .notif-content small,
        body.dark-mode .notif-time {
            color: #94a3b8 !important;
        }

        body.dark-mode .table,
        body.dark-mode .table tbody tr,
        body.dark-mode .table th,
        body.dark-mode .table td {
            color: #e6eef8 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }

        body.dark-mode .table tbody tr:hover {
            background: rgba(255,255,255,0.05) !important;
        }

        body.dark-mode .auth-cover-wrapper.bg-primary-100 {
            background: rgba(255,255,255,0.04);
        }

        body.dark-mode .auth-cover .title h1,
        body.dark-mode .auth-cover .title p {
            color: #e6eef8 !important;
        }

        /* Make notification badge more legible */
        .notif-badge {
            font-weight:700;
            box-shadow: 0 4px 12px rgba(2,6,23,0.12);
        }
    </style>
</head>

<body class="@yield('body-class')">

 @if(session('success'))
    <div class="alert alert-success floating-alert flash-alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger floating-alert flash-alert">
        {{ session('error') }}
    </div>
@endif

 @if(Auth::check())
    <div class="sidebar">
        <div class="logo">CollabTrack</div>

        {{-- ADMIN MENU --}}
        @if(auth()->user()->role === 'admin')

            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="lni lni-dashboard me-2"></i> Dashboard
            </a>

            <a href="/teams" class="{{ request()->is('teams') ? 'active' : '' }}">
                <i class="lni lni-users me-2"></i> View Teams
            </a>

            <a href="/teams/create" class="{{ request()->is('teams/create') ? 'active' : '' }}">
                <i class="lni lni-plus me-2"></i> Create Team
            </a>

            <a href="/tasks" class="{{ request()->is('tasks') ? 'active' : '' }}">
                <i class="lni lni-checkmark-circle me-2"></i> Tasks
            </a>

            <a href="/reports">
                <i class="lni lni-bar-chart me-2"></i> Reports
            </a>

        {{-- MANAGER MENU --}}
        @elseif(auth()->user()->role === 'manager')

            <a href="/manager/dashboard" class="{{ request()->is('manager/dashboard') ? 'active' : '' }}">
                <i class="lni lni-dashboard me-2"></i> Manager Dashboard
            </a>

            <a href="/teams" class="{{ request()->is('teams') ? 'active' : '' }}">
                <i class="lni lni-users me-2"></i> Teams
            </a>

            <a href="/tasks" class="{{ request()->is('tasks') ? 'active' : '' }}">
                <i class="lni lni-checkmark-circle me-2"></i> Tasks
            </a>

            <a href="/reports">
                <i class="lni lni-bar-chart me-2"></i> Reports
            </a>

        {{-- USER MENU --}}
        @else

            <a href="/user/dashboard" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
                <i class="lni lni-dashboard me-2"></i> My Dashboard
            </a>

            <a href="/tasks" class="{{ request()->is('tasks') ? 'active' : '' }}">
                <i class="lni lni-checkmark-circle me-2"></i> My Tasks
            </a>

        @endif

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button type="submit" class="logout-btn w-100">
                <i class="lni lni-exit me-2"></i> Logout
            </button>
        </form>
    </div>
  <div class="main-content">

 <!-- TOP RIGHT BAR -->

@if(Auth::check())

<div class="d-flex justify-content-end mb-3">


@php

$notifications = auth()->user()
    ->unreadNotifications()
    ->latest()
    ->take(5)
    ->get();

@endphp



<div class="me-3 d-flex align-items-center">
    <div class="theme-switch" role="switch" aria-checked="false" tabindex="0" style="margin-right:6px;">
        <input type="checkbox" id="darkModeToggle" style="display:none;">
        <label for="darkModeToggle" class="switch-label" aria-label="Toggle dark mode">
            <span class="switch-slider" aria-hidden="true"></span>
            <span class="switch-text visually-hidden">Toggle dark mode</span>
        </label>
    </div>
</div>

<div class="dropdown">


<button class="notification-btn dropdown-toggle"
        data-bs-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false">
    <span class="notification-icon" aria-hidden="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C10.3431 2 9 3.34315 9 5V6.08185C6.16639 7.02146 4 9.72656 4 13V16L2 18V19H22V18L20 16V13C20 9.72656 17.8336 7.02146 15 6.08185V5C15 3.34315 13.6569 2 12 2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 19C8 20.6569 9.34315 22 11 22H13C14.6569 22 16 20.6569 16 19" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
    </span>
    <span class="visually-hidden">Notifications</span>

@if(auth()->user()->unreadNotifications->count())


<span class="notif-badge">

{{auth()->user()->unreadNotifications->count()}}

</span>


@endif



</button>


<div class="dropdown-menu dropdown-menu-end notif-dropdown p-0">


<div class="notif-header d-flex justify-content-between">

<span>
Notifications
</span>


<span>

{{auth()->user()->unreadNotifications->count()}}

</span>


</div>




@forelse($notifications as $notification)

<div class="notif-item unread">
    <div class="notif-icon" aria-hidden="true">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C10.3431 2 9 3.34315 9 5V6.08185C6.16639 7.02146 4 9.72656 4 13V16L2 18V19H22V18L20 16V13C20 9.72656 17.8336 7.02146 15 6.08185V5C15 3.34315 13.6569 2 12 2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 19C8 20.6569 9.34315 22 11 22H13C14.6569 22 16 20.6569 16 19" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
    </div>



<div class="notif-content">


<p>

{{$notification->data['message']}}

</p>


<small class="notif-time">

{{$notification->created_at->diffForHumans()}}

</small>


@if(isset($notification->data['task_id']))

<br>


<a href="/tasks/{{$notification->data['task_id']}}"

class="btn btn-sm btn-primary mt-2">

View Task

</a>


@endif




<a href="/notifications/read/{{$notification->id}}"

class="btn btn-sm btn-outline-secondary mt-2">

Mark read

</a>



</div>



</div>



@empty


<div class="p-3 text-center">

No notifications

</div>



@endforelse



</div>


</div>


</div>

@endif
    @yield('content')
</div>
    @else
        @yield('content')

        @if(!Auth::check())
            <footer class="auth-footer">
                <p>CollabTrack | Professional team and task management for your capstone project.</p>
                <p>Designed with clean workflows, secure login, and responsive team controls.</p>
            </footer>
        @endif
    @endif

    <!-- PlainAdmin JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

   <script>
    function toggleLoginPassword() {
        const password = document.getElementById('loginPassword');
        if (password) {
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    }

    function toggleRegisterPassword() {
        const password = document.getElementById('registerPassword');
        if (password) {
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    }

    function toggleConfirmPassword() {
        const password = document.getElementById('confirmPassword');
        if (password) {
            password.type = password.type === 'password' ? 'text' : 'password';
        }
    }

    function setDarkMode(enabled) {
        if (enabled) {
            document.body.classList.add('dark-mode');
        } else {
            document.body.classList.remove('dark-mode');
        }
        localStorage.setItem('darkMode', enabled ? 'true' : 'false');
        const chk = document.getElementById('darkModeToggle');
        if (chk) chk.checked = enabled;
        // Update aria attribute for assistive tech
        const sw = document.querySelector('.theme-switch');
        if (sw) sw.setAttribute('aria-checked', enabled ? 'true' : 'false');
    }

    function toggleDarkMode() {
        setDarkMode(!document.body.classList.contains('dark-mode'));
    }

    window.onload = function () {
        const stored = localStorage.getItem('darkMode');
        const enabled = stored === 'true';
        setDarkMode(enabled);

        const chk = document.getElementById('darkModeToggle');
        if (chk) {
            chk.addEventListener('change', function() {
                setDarkMode(this.checked);
            });
        }

        // allow keyboard toggle on the visible label wrapper
        const themeSwitchLabel = document.querySelector('.theme-switch');
        if (themeSwitchLabel) {
            themeSwitchLabel.addEventListener('keydown', function(e){
                if(e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    const c = document.getElementById('darkModeToggle');
                    if (c) { c.checked = !c.checked; c.dispatchEvent(new Event('change')); }
                }
            });
        }

        const alerts = document.querySelectorAll('.flash-alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = '0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 500);
            }, 2000);
        });
    };
</script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
@yield('scripts')
<script>

@if(auth()->check())

window.echo = new Echo({

    broadcaster: 'pusher',

    key: "{{ env('PUSHER_APP_KEY') }}",

    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",

    forceTLS: true

});


window.echo.private(
    "App.Models.User.{{ auth()->id() }}"
)

.notification((notification)=>{

    console.log(notification);

    showNotification(notification.message);

});


@endif


function showNotification(message){

    let popup = document.getElementById('notification-popup');

    if(popup){

        popup.innerHTML = "🔔 " + message;

        popup.style.display = "block";

        setTimeout(()=>{

            popup.style.display = "none";

        },5000);

    }

}

</script>
<audio id="notification-sound"
src="{{asset('sounds/notification.mp3')}}">
</audio>


<div id="notification-popup"></div>


</body>
</html>
