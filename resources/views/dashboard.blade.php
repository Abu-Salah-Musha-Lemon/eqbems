
@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<div class="page-heading">
    <div>
        <div class="eyebrow">Dashboard / Overview</div>
        <h1 class="page-title">Welcome back, {{ auth()->user()->name ?? 'Administrator' }}!</h1>
        <div class="page-description">
            Here's what's happening in your education management system.
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-light"
                onclick="window.location.reload()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </button>
        <a href="{{ route('users.index') }}" class="btn btn-gold">
            <i class="bi bi-plus-lg me-1"></i> Manage Users
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-pro stat-card">
            <div class="stat-top">
                <div class="stat-icon navy"><i class="bi bi-people-fill"></i></div>
                <span class="stat-trend">Users</span>
            </div>
            <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-note">Registered system users</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-pro stat-card">
            <div class="stat-top">
                <div class="stat-icon gold"><i class="bi bi-buildings"></i></div>
                <span class="stat-trend">Institutions</span>
            </div>
            <div class="stat-value">{{ $totalInstitutions ?? 0 }}</div>
            <div class="stat-label">Total Institutions</div>
            <div class="stat-note">Institutions in the system</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-pro stat-card">
            <div class="stat-top">
                <div class="stat-icon blue"><i class="bi bi-journal-check"></i></div>
                <span class="stat-trend">Exams</span>
            </div>
            <div class="stat-value">{{ $totalExaminations ?? 0 }}</div>
            <div class="stat-label">Examinations</div>
            <div class="stat-note">Total examinations</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-pro stat-card">
            <div class="stat-top">
                <div class="stat-icon green"><i class="bi bi-patch-check-fill"></i></div>
                <span class="stat-trend">Active</span>
            </div>
            <div class="stat-value">{{ $activeUsers ?? 0 }}</div>
            <div class="stat-label">Active Users</div>
            <div class="stat-note">Currently active accounts</div>
        </div>
    </div>
</div>

<!-- Charts and activity -->
<div class="row g-3 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card-pro">
            <div class="card-pro-header">
                <div>
                    <h2 class="section-title">Registration Overview</h2>
                    <div class="section-sub">Monthly registration activity</div>
                </div>
                <span class="badge rounded-pill text-bg-light">This year</span>
            </div>
            <div class="card-pro-body">
                <div class="chart-wrap">
                    <canvas id="activityChart"
                            aria-label="Monthly registration chart"
                            role="img"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card-pro">
            <div class="card-pro-header">
                <div>
                    <h2 class="section-title">Recent Activity</h2>
                    <div class="section-sub">System activity summary</div>
                </div>
                <i class="bi bi-clock-history text-secondary"></i>
            </div>

            <div class="card-pro-body">
                <div class="activity-item">
                    <div class="activity-icon"><i class="bi bi-person-plus"></i></div>
                    <div>
                        <div class="activity-title">User Management</div>
                        <div class="activity-desc">Review registered accounts.</div>
                        <div class="activity-time">User administration</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon" style="background:#fff4d9;color:#a47a2c">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <div class="activity-title">Institution Management</div>
                        <div class="activity-desc">Review institution records.</div>
                        <div class="activity-time">Institution administration</div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon" style="background:#e3f7ee;color:#17865d">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="activity-title">Account Security</div>
                        <div class="activity-desc">Keep your account details up to date.</div>
                        <div class="activity-time">Administrator workspace</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick actions -->
<div class="card-pro mb-4">
    <div class="card-pro-header">
        <div>
            <h2 class="section-title">Quick Actions</h2>
            <div class="section-sub">Navigate to frequently used modules.</div>
        </div>
    </div>

    <div class="card-pro-body">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('users.index') }}" class="quick-action">
                    <i class="bi bi-people"></i>
                    <span>
                        <strong>Manage Users</strong>
                        <small>View and manage accounts</small>
                    </span>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('institutions.index') }}" class="quick-action">
                    <i class="bi bi-buildings"></i>
                    <span>
                        <strong>Institutions</strong>
                        <small>Manage institutions</small>
                    </span>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="{{ route('profile.edit') }}" class="quick-action">
                    <i class="bi bi-person-gear"></i>
                    <span>
                        <strong>My Profile</strong>
                        <small>Update account details</small>
                    </span>
                </a>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <a href="#examinations" class="quick-action"
                   data-coming-soon="Examinations">
                    <i class="bi bi-journal-check"></i>
                    <span>
                        <strong>Examinations</strong>
                        <small>Examination module</small>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
