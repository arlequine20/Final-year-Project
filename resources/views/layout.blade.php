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
            background-color: #f5f7fb;
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
            background: white;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: 0.3s ease;
        }

        .page-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 30px;
            transition: 0.3s ease;
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

        .theme-btn {
            border: none;
            background: #111827;
            color: white;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 500;
            margin-top: 10px;
            width: 100%;
        }

        .theme-btn:hover {
            background: #1f2937;
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
            background-color: #111827;
            color: #f9fafb;
        }

        body.dark-mode .sidebar {
            background: #1f2937;
            border-right: 1px solid #374151;
        }

        body.dark-mode .sidebar a {
            color: #f9fafb;
        }

        body.dark-mode .sidebar a:hover,
        body.dark-mode .sidebar a.active {
            background-color: #16a34a;
            color: white;
        }

        body.dark-mode .topbar,
        body.dark-mode .page-card {
            background: #1f2937;
            color: #f9fafb;
            box-shadow: none;
        }

        body.dark-mode .text-muted {
            color: #d1d5db !important;
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
            background-color: #111827 !important;
            color: #f9fafb !important;
            border: 1px solid #374151 !important;
        }

        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder {
            color: #cbd5e1 !important;
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
            color: #f9fafb !important;
        }

        /* =========================
           AUTH PAGE FIX (LOGIN / REGISTER)
        ========================== */
        body.dark-mode.auth-page {
            background-color: #0f172a !important;
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page .signin-wrapper,
        body.dark-mode.auth-page .signup-wrapper,
        body.dark-mode.auth-page .form-wrapper {
            background: #111827 !important;
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page .auth-cover-wrapper {
            background: #1e293b !important;
        }

        body.dark-mode.auth-page .auth-cover,
        body.dark-mode.auth-page .auth-cover .title,
        body.dark-mode.auth-page .auth-cover h1,
        body.dark-mode.auth-page .auth-cover p {
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page .signin-wrapper h1,
        body.dark-mode.auth-page .signin-wrapper h2,
        body.dark-mode.auth-page .signin-wrapper h3,
        body.dark-mode.auth-page .signin-wrapper h4,
        body.dark-mode.auth-page .signin-wrapper h5,
        body.dark-mode.auth-page .signin-wrapper h6,
        body.dark-mode.auth-page .signin-wrapper p,
        body.dark-mode.auth-page .signin-wrapper label,
        body.dark-mode.auth-page .signup-wrapper h1,
        body.dark-mode.auth-page .signup-wrapper h2,
        body.dark-mode.auth-page .signup-wrapper h3,
        body.dark-mode.auth-page .signup-wrapper h4,
        body.dark-mode.auth-page .signup-wrapper h5,
        body.dark-mode.auth-page .signup-wrapper h6,
        body.dark-mode.auth-page .signup-wrapper p,
        body.dark-mode.auth-page .signup-wrapper label,
        body.dark-mode.auth-page .text-sm,
        body.dark-mode.auth-page .text-medium,
        body.dark-mode.auth-page .text-dark,
        body.dark-mode.auth-page .text-light,
        body.dark-mode.auth-page .form-wrapper p,
        body.dark-mode.auth-page .form-wrapper label {
            color: #f8fafc !important;
        }

        body.dark-mode.auth-page input,
        body.dark-mode.auth-page select,
        body.dark-mode.auth-page textarea {
            background: #1f2937 !important;
            color: #ffffff !important;
            border: 1px solid #374151 !important;
        }

        body.dark-mode.auth-page input::placeholder,
        body.dark-mode.auth-page textarea::placeholder {
            color: #cbd5e1 !important;
        }

        body.dark-mode.auth-page a {
            color: #22c55e !important;
        }

        body.dark-mode.auth-page .alert-success {
            background: #14532d !important;
            color: #dcfce7 !important;
            border: none;
        }

        body.dark-mode.auth-page .alert-danger {
            background: #7f1d1d !important;
            color: #fee2e2 !important;
            border: none;
        }
        body.dark-mode table {
    color: #f9fafb !important;
}

body.dark-mode .table {
    color: #f9fafb !important;
    background-color: transparent !important;
}

body.dark-mode .table thead th {
    color: #f9fafb !important;
    border-color: #374151 !important;
    background-color: #111827 !important;
}

body.dark-mode .table tbody tr {
    background-color: transparent !important;
}

body.dark-mode .table tbody td {
    color: #f9fafb !important;
    border-color: #374151 !important;
}

body.dark-mode .table-hover tbody tr:hover {
    background-color: #1f2937 !important;
}
body.dark-mode .form-control {
    background-color: #111827 !important;
    color: #f9fafb !important;
    border: 1px solid #374151 !important;
}

body.dark-mode .form-control option {
    background-color: #111827 !important;
    color: #f9fafb !important;
}
/* =========================
   CUSTOM ACTION BUTTONS
========================= */
.btn-edit-custom {
    display: inline-block;
    background-color: #2563eb !important;
    color: #ffffff !important;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none !important;
    font-size: 14px;
    font-weight: 600;
    border: none !important;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    transition: all 0.3s ease;
}

.btn-edit-custom:hover {
    background-color: #1d4ed8 !important;
    color: #ffffff !important;
}

.btn-delete-custom {
    background-color: #dc2626 !important;
    color: #ffffff !important;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    transition: 0.3s ease;
}

.btn-delete-custom:hover {
    background-color: #b91c1c;
    color: white !important;
}

/* DARK MODE */
body.dark-mode .btn-edit-custom {
    background-color: #3b82f6;
    color: white !important;
}

body.dark-mode .btn-edit-custom:hover {
    background-color: #2563eb;
}

body.dark-mode .btn-delete-custom {
    background-color: #ef4444;
    color: white !important;
}

body.dark-mode .btn-delete-custom:hover {
    background-color: #dc2626;
}
.team-card {
    transition: 0.3s ease;
    border: 1px solid #f1f5f9;
}

.team-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}
.btn-manage-members {
    background-color: #f1f5f9;
    color: #334155;
    padding: 8px 14px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #e2e8f0;
    transition: 0.3s ease;
}

.btn-manage-members:hover {
    background-color: #e2e8f0;
    color: #1e293b;
}

/* DARK MODE */
body.dark-mode .btn-manage-members {
    background-color: #021636;
    color: #e5e7eb;
    border: 1px solid #4b5563;
}

body.dark-mode .btn-manage-members:hover {
    background-color: #4b5563;
    color: white;
}
/* Softer primary button */
.primary-btn {
    background-color: #2563eb; /* softer blue */
    border-color: #2563eb;
    color: #fff;
}

.primary-btn:hover {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
}

/* Muted button (for Trash / Back) */
.muted-btn {
    background-color: #16a34a;
    color: #ffffff;
    border: none;
}

.muted-btn:hover {
    background-color: #21c560;
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

        {{-- USER MENU --}}
        @else

            <a href="/user/dashboard" class="{{ request()->is('user/dashboard') ? 'active' : '' }}">
                <i class="lni lni-dashboard me-2"></i> My Dashboard
            </a>

            <a href="/tasks" class="{{ request()->is('tasks') ? 'active' : '' }}">
                <i class="lni lni-checkmark-circle me-2"></i> My Tasks
            </a>

        @endif

        {{-- COMMON BUTTONS --}}
        <button onclick="toggleDarkMode()" class="theme-btn">
            🌙 Dark Mode
        </button>

        <form method="POST" action="/logout" class="mt-4">
            @csrf
            <button type="submit" class="logout-btn w-100">
                <i class="lni lni-exit me-2"></i> Logout
            </button>
        </form>
    </div>
  <div class="main-content">
            @yield('content')
        </div>
    @else
        @yield('content')
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

    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    }

    window.onload = function () {
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
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
@yield('scripts')
</body>
</html>