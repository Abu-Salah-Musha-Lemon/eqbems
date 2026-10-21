<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · {{ config('app.name', 'EQBEMS') }} Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #14213d;
            --navy2: #1d3156;
            --blue: #4263eb;
            --ink: #1f2937;
            --muted: #7b8798;
            --line: #e9edf4;
            --canvas: #f5f7fb;
            --gold: #d6a84f;
            --sidebar: 258px
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: var(--canvas);
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -.025em
        }

        .admin-shell {
            min-height: 100vh
        }

        .sidebar {
            width: var(--sidebar);
            background: linear-gradient(180deg, var(--navy), #101a30);
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1040;
            color: #dbe5f5;
            display: flex;
            flex-direction: column;
            padding: 23px 15px;
            transition: transform .25s
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
            font-weight: 800;
            font-size: 19px;
            padding: 0 10px 26px
        }

        .brand-mark {
            width: 39px;
            height: 39px;
            border-radius: 12px;
            background: linear-gradient(135deg, #e9c574, #c58d2c);
            display: grid;
            place-items: center;
            color: #18223a;
            font-size: 21px;
            box-shadow: 0 6px 20px #0002
        }

        .brand small {
            display: block;
            color: #9facc4;
            font: 500 10px 'DM Sans';
            letter-spacing: .16em;
            margin-top: 2px
        }

        .nav-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #71809b;
            padding: 14px 12px 8px
        }

        .side-link {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #aebbd1;
            text-decoration: none;
            padding: 11px 13px;
            border-radius: 10px;
            margin: 2px 0;
            font-weight: 600;
            transition: .15s
        }

        .side-link i {
            font-size: 17px;
            width: 21px;
            text-align: center
        }

        .side-link:hover,
        .side-link.active {
            background: #ffffff12;
            color: white
        }

        .side-link.active {
            box-shadow: inset 3px 0 var(--gold);
            background: linear-gradient(90deg, #ffffff12, #ffffff05)
        }

        .sidebar-bottom {
            margin-top: auto;
            border-top: 1px solid #ffffff15;
            padding-top: 15px
        }

        .main {
            margin-left: var(--sidebar);
            min-height: 100vh
        }

        .topbar {
            height: 76px;
            background: #fff;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 1020
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px
        }

        .topbar-title {
            font-weight: 700;
            font-size: 15px
        }

        .topbar-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 2px
        }

        .icon-btn {
            height: 39px;
            width: 39px;
            border: 1px solid var(--line);
            border-radius: 11px;
            background: #fff;
            color: #566176;
            display: grid;
            place-items: center
        }

        .avatar {
            height: 38px;
            width: 38px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #e9efff;
            color: #3555c7;
            font-weight: 800
        }

        .page-content {
            padding: 30px 32px 38px;
            max-width: 1600px;
            margin: auto
        }

        .eyebrow {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .13em;
            font-weight: 700;
            color: var(--blue)
        }

        .page-title {
            font-size: 27px;
            font-weight: 800;
            margin: 7px 0
        }

        .muted {
            color: var(--muted)
        }

        .card-pro {
            border: 1px solid var(--line);
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 3px 14px #1d315607
        }

        .stat-card {
            padding: 21px;
            position: relative;
            overflow: hidden;
            height: 100%
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 13px;
            display: grid;
            place-items: center;
            font-size: 20px
        }

        .stat-value {
            font: 800 29px 'Plus Jakarta Sans';
            letter-spacing: -.04em;
            margin: 18px 0 4px
        }

        .stat-label {
            color: var(--muted);
            font-weight: 600;
            font-size: 12px
        }

        .stat-foot {
            font-size: 11px;
            color: #17865d;
            margin-top: 12px
        }

        .section-title {
            font-weight: 800;
            font-size: 15px;
            margin: 0
        }

        .section-sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 5px
        }

        .chart-wrap {
            height: 280px;
            padding-top: 24px
        }

        .activity-item {
            display: flex;
            gap: 13px;
            padding: 14px 0;
            border-bottom: 1px solid #f0f2f7
        }

        .activity-item:last-child {
            border-bottom: 0
        }

        .activity-icon {
            width: 35px;
            height: 35px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            flex-shrink: 0
        }

        .activity-title {
            font-weight: 700;
            font-size: 12px
        }

        .activity-time {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: 11px;
            font-weight: 700;
            background: #e8f8ef;
            color: #168453
        }

        .status-pill:before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 13px;
            text-decoration: none;
            color: var(--ink);
            padding: 14px;
            border: 1px solid var(--line);
            border-radius: 12px;
            transition: .18s
        }

        .quick-action:hover {
            border-color: #bdcaff;
            background: #f8faff;
            transform: translateY(-1px)
        }

        .quick-action i {
            font-size: 19px;
            color: var(--blue)
        }

        .quick-action strong {
            display: block;
            font-size: 12px
        }

        .quick-action small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            margin-top: 3px
        }

        .btn-primary {
            background: var(--blue);
            border-color: var(--blue);
            border-radius: 10px;
            font-weight: 700;
            padding: 10px 15px
        }

        .btn-light {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 10px
        }

        .table-card {
            padding: 23px
        }

        .table>:not(caption)>*>* {
            padding: 13px 12px;
            border-bottom-color: #edf0f5
        }

        .table thead th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #7b8798;
            background: #fafbfe;
            font-weight: 800;
            white-space: nowrap
        }

        .table tbody td {
            vertical-align: middle
        }

        .form-control,
        .form-select {
            border-color: #dfe5ef;
            border-radius: 9px;
            padding: 10px 12px
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #8ca1ff;
            box-shadow: 0 0 0 .2rem #4263eb1c
        }

        .footer-note {
            font-size: 11px;
            color: #8b96a8;
            padding-top: 22px
        }

        .mobile-menu {
            display: none
        }

        @media(max-width:991px) {
            .sidebar {
                transform: translateX(-100%)
            }

            .sidebar.open {
                transform: translateX(0);
                box-shadow: 10px 0 35px #101a3040
            }

            .main {
                margin-left: 0
            }

            .mobile-menu {
                display: grid
            }

            .topbar {
                padding: 0 18px
            }

            .page-content {
                padding: 22px 17px
            }

            .page-title {
                font-size: 23px
            }
        }

        @media(max-width:575px) {
            .topbar {
                height: 65px
            }

            .topbar-sub {
                display: none
            }

            .page-content {
                padding: 18px 12px
            }

            .stat-card {
                padding: 16px
            }

            .stat-value {
                font-size: 25px
            }

            .hide-xs {
                display: none !important
            }
        }

    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-shell">
        <aside class="sidebar" id="adminSidebar">
            <a href="{{ route('dashboard') }}" class="brand"><span class="brand-mark"><i
                        class="bi bi-mortarboard-fill"></i></span><span>EQBEMS<small>EDUCATION
                        MANAGEMENT</small></span></a>
            <div class="nav-label">Workspace</div>
            <a class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill"></i> Overview</a>
            @can('institution.view')
            <a class="side-link {{ request()->routeIs('institutions.*') ? 'active' : '' }}" href="{{ route('institutions.index') }}"><i class="bi bi-buildings"></i> Institutions</a>
            @endcan
            @can('academic.view')
            <div class="nav-label">Academic</div>
            <a class="side-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}"><i class="bi bi-book"></i> Subjects & topics</a>
            <a class="side-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}" href="{{ route('academic-years.index') }}"><i class="bi bi-calendar3"></i> Academic years</a>
            @endcan
            <div class="nav-label">Examination</div>
            @can('question.view')<a class="side-link {{ request()->routeIs('questions.*') && !request()->routeIs('questions.review-queue') ? 'active' : '' }}" href="{{ route('questions.index') }}"><i class="bi bi-journal-text"></i> Question bank</a>@endcan
            @can('question.view')
                @can('question.review')
                <a class="side-link {{ request()->routeIs('questions.review-queue') ? 'active' : '' }}" href="{{ route('questions.review-queue') }}"><i class="bi bi-check2-square"></i> Review questions</a>
                @endcan
            @endcan
            @can('blueprint.view')<a class="side-link {{ request()->routeIs('blueprints.*') ? 'active' : '' }}" href="{{ route('blueprints.index') }}"><i class="bi bi-diagram-3"></i> Blueprints</a>@endcan
            @can('exam.view')<a class="side-link {{ request()->routeIs('exams.*') ? 'active' : '' }}" href="{{ route('exams.index') }}"><i class="bi bi-clipboard2-check"></i> Examinations</a>@endcan
            @role('Student')<a class="side-link {{ request()->routeIs('online-exams.*') ? 'active' : '' }}" href="{{ route('online-exams.index') }}"><i class="bi bi-laptop"></i> My online exams</a>@endrole
            @can('result.mark')<a class="side-link {{ request()->routeIs('online-attempts.*') ? 'active' : '' }}" href="{{ route('online-attempts.index') }}"><i class="bi bi-pencil-square"></i> Grade online attempts</a>@endcan
            <a class="side-link {{ request()->routeIs('results.*') ? 'active' : '' }}" href="{{ route('results.index') }}"><i class="bi bi-award"></i> Results</a>
            <div class="nav-label">System</div>
            @role('Super Admin')<a class="side-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people"></i> User management</a>@endrole
            <a class="side-link" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear"></i> My profile</a>
            <div class="sidebar-bottom">
                <div class="d-flex align-items-center gap-2 px-2">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</div>
                    <div class="min-w-0">
                        <div class="text-white fw-bold text-truncate" style="max-width:145px">
                            {{ auth()->user()->name ?? 'Administrator' }}</div>
                        <div style="font-size:11px;color:#91a0b9">Signed in</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="ms-auto">@csrf<button class="icon-btn"
                            title="Sign out" aria-label="Sign out"><i class="bi bi-box-arrow-right"></i></button></form>
                </div>
            </div>
        </aside>
        <div class="main">
            <header class="topbar">
                <div class="topbar-left"><button class="icon-btn mobile-menu" id="sidebarToggle"
                        aria-label="Toggle menu"><i class="bi bi-list fs-5"></i></button>
                    <div>
                        <div class="topbar-title">{{ config('app.name', 'EQBEMS') }} <span class="muted fw-normal">/
                                @yield('title','Overview')</span></div>
                        <div class="topbar-sub">Education, question bank & examination management</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end hide-xs">
                        <div class="fw-bold" style="font-size:12px">{{ now()->format('D, d M Y') }}</div>
                        <div class="muted" style="font-size:11px">Administrator workspace</div>
                    </div>
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}</div>
                </div>
            </header>
            <main class="page-content">
                @if(session('success'))<div class="alert alert-success alert-dismissible fade show" role="alert"><i
                        class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button"
                        class="btn-close" data-bs-dismiss="alert"></button></div>@endif
                @if(session('error'))<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>@endif
                @if($errors->any())<div class="alert alert-danger"><strong>Please check the form:</strong>
                    <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>@endif
                @yield('content')
                <div class="footer-note d-flex flex-wrap justify-content-between gap-2"><span>© {{ now()->year }} EQBEMS
                        · Admin Console</span><span>Built for reliable education management</span></div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const sidebar = document.getElementById('adminSidebar');
            document.getElementById('sidebarToggle')?.addEventListener('click', () => sidebar.classList.toggle(
                'open'));
            if (window.toastr) toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3500
            };
            @if(session('success')) if (window.toastr) toastr.success(@json(session('success')));
            @endif
            @if(session('error')) if (window.toastr) toastr.error(@json(session('error')));
            @endif
            @if(session('warning')) if (window.toastr) toastr.warning(@json(session('warning')));
            @endif
            document.querySelectorAll('[data-confirm]').forEach(el => el.addEventListener('click', async function (
                e) {
                e.preventDefault();
                const form = this.closest('form');
                const result = window.Swal ? await Swal.fire({
                    title: this.dataset.confirmTitle || 'Are you sure?',
                    text: this.dataset.confirm || 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4263eb',
                    cancelButtonColor: '#7b8798',
                    confirmButtonText: this.dataset.confirmButton || 'Yes, continue',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }) : {isConfirmed: window.confirm(this.dataset.confirm || 'Proceed?')};
                if (result.isConfirmed) {
                    if (form) form.submit();
                    else if (this.href) window.location = this.href;
                }
            }));

        })();

    </script>
    @stack('scripts')
</body>

</html>
