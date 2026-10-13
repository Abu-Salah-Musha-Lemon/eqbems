<?php $__env->startSection('title', 'Overview'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <div class="eyebrow">Dashboard / Overview</div>

            <h1 class="page-title">
                Good
                <?php echo e(now()->hour < 12
                    ? 'morning'
                    : (now()->hour < 17 ? 'afternoon' : 'evening')); ?>,
                <?php echo e(explode(' ', auth()->user()->name ?? 'Administrator')[0]); ?>

                <span aria-hidden="true">✦</span>
            </h1>

            <p class="muted mb-0">
                Here’s what’s happening across your education platform.
            </p>
        </div>

        <a href="<?php echo e(route('institutions.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>
            Add institution
        </a>
    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-pro stat-card">
                <div class="stat-top">
                    <span class="stat-label">TOTAL USERS</span>

                    <span
                        class="stat-icon"
                        style="background:#eaf0ff;color:#4263eb"
                    >
                        <i class="bi bi-people"></i>
                    </span>
                </div>

                <div class="stat-value">
                    <?php echo e(number_format($userCount)); ?>

                </div>

                <div class="muted" style="font-size:12px">
                    Registered platform accounts
                </div>

                <div class="stat-foot">
                    <i class="bi bi-arrow-up-right me-1"></i>
                    <?php echo e(number_format($newUsersThisMonth)); ?> joined this month
                </div>
            </div>
        </div>

        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-pro stat-card">
                <div class="stat-top">
                    <span class="stat-label">INSTITUTIONS</span>

                    <span
                        class="stat-icon"
                        style="background:#e4f8ef;color:#168453"
                    >
                        <i class="bi bi-buildings"></i>
                    </span>
                </div>

                <div class="stat-value">
                    <?php echo e(number_format($institutionCount)); ?>

                </div>

                <div class="muted" style="font-size:12px">
                    Education organizations
                </div>

                <div class="stat-foot">
                    <i class="bi bi-check-circle me-1"></i>
                    <?php echo e(number_format($activeInstitutions)); ?> active
                </div>
            </div>
        </div>

        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-pro stat-card">
                <div class="stat-top">
                    <span class="stat-label">QUESTION BANK</span>

                    <span
                        class="stat-icon"
                        style="background:#fff2df;color:#b7791f"
                    >
                        <i class="bi bi-journal-text"></i>
                    </span>
                </div>

                <div class="stat-value">
                    <?php echo e($moduleCounts['questions'] === null
                        ? '—'
                        : number_format($moduleCounts['questions'])); ?>

                </div>

                <div class="muted" style="font-size:12px">
                    Question records
                </div>

                <div class="stat-foot" style="color:#9a7a3c">
                    <i class="bi bi-info-circle me-1"></i>
                    <?php echo e($moduleCounts['questions'] === null
                        ? 'Module not yet installed'
                        : 'Database records'); ?>

                </div>
            </div>
        </div>

        
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card-pro stat-card">
                <div class="stat-top">
                    <span class="stat-label">EXAMINATIONS</span>

                    <span
                        class="stat-icon"
                        style="background:#f1eaff;color:#805ad5"
                    >
                        <i class="bi bi-clipboard2-check"></i>
                    </span>
                </div>

                <div class="stat-value">
                    <?php echo e($moduleCounts['exams'] === null
                        ? '—'
                        : number_format($moduleCounts['exams'])); ?>

                </div>

                <div class="muted" style="font-size:12px">
                    Exam records
                </div>

                <div class="stat-foot" style="color:#805ad5">
                    <i class="bi bi-info-circle me-1"></i>
                    <?php echo e($moduleCounts['exams'] === null
                        ? 'Module not yet installed'
                        : 'Database records'); ?>

                </div>
            </div>
        </div>

    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-12 col-xl-8">
            <section class="card-pro p-4 h-100">
                <div
                    class="d-flex flex-wrap justify-content-between
                    align-items-start gap-2"
                >
                    <div>
                        <h2 class="section-title">User growth</h2>

                        <div class="section-sub">
                            New account registrations · last six months
                        </div>
                    </div>

                    <span class="badge rounded-pill text-bg-light border px-3 py-2">
                        Live database
                    </span>
                </div>

                <div class="chart-wrap">
                    <canvas
                        id="userGrowthChart"
                        aria-label="User registrations over the last six months"
                        role="img"
                    ></canvas>
                </div>
            </section>
        </div>

        
        <div class="col-12 col-xl-4">
            <section class="card-pro p-4 h-100">
                <h2 class="section-title">Institution status</h2>

                <div class="section-sub">
                    Active versus inactive institutions
                </div>

                <div
                    style="height:210px;position:relative;margin-top:14px"
                >
                    <canvas
                        id="institutionChart"
                        aria-label="Institution status chart"
                        role="img"
                    ></canvas>
                </div>

                <div class="d-flex justify-content-around mt-2">
                    <div>
                        <span
                            class="d-inline-block rounded-circle me-2"
                            style="width:8px;height:8px;background:#4263eb"
                        ></span>

                        <span class="muted">Active</span>
                        <strong><?php echo e($activeInstitutions); ?></strong>
                    </div>

                    <div>
                        <span
                            class="d-inline-block rounded-circle me-2"
                            style="width:8px;height:8px;background:#e8edf5"
                        ></span>

                        <span class="muted">Inactive</span>
                        <strong><?php echo e($inactiveInstitutions); ?></strong>
                    </div>
                </div>
            </section>
        </div>

    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-12 col-xl-7">
            <section class="card-pro table-card h-100">
                <div
                    class="d-flex justify-content-between
                    align-items-start gap-2 mb-3"
                >
                    <div>
                        <h2 class="section-title">
                            Recently added institutions
                        </h2>

                        <div class="section-sub">
                            Latest organizations in your system
                        </div>
                    </div>

                    <a
                        class="btn btn-light btn-sm px-3"
                        href="<?php echo e(route('institutions.index')); ?>"
                    >
                        View all
                        <i class="bi bi-arrow-up-right ms-1"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentInstitutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold">
                                            <?php echo e($institution->name); ?>

                                        </div>

                                        <div
                                            class="muted"
                                            style="font-size:11px"
                                        >
                                            <?php echo e($institution->email ?: 'No email added'); ?>

                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge text-bg-light border">
                                            <?php echo e($institution->code); ?>

                                        </span>
                                    </td>

                                    <td>
                                        <span
                                            class="status-pill"
                                            style="<?php echo e($institution->status
                                                ? ''
                                                : 'background:#fff0f0;color:#bd4242'); ?>"
                                        >
                                            <?php echo e($institution->status
                                                ? 'Active'
                                                : 'Inactive'); ?>

                                        </span>
                                    </td>

                                    <td class="muted text-nowrap">
                                        <?php echo e($institution->created_at?->format('d M Y') ?? '—'); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i
                                            class="bi bi-buildings fs-3
                                            d-block mb-2 muted"
                                        ></i>

                                        <div class="fw-bold">
                                            No institutions yet
                                        </div>

                                        <div class="muted mt-1">
                                            Add your first institution to get started.
                                        </div>

                                        <a
                                            href="<?php echo e(route('institutions.create')); ?>"
                                            class="btn btn-primary btn-sm mt-3"
                                        >
                                            Add institution
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        
        <div class="col-12 col-xl-5">
            <section class="card-pro p-4 h-100">
                <h2 class="section-title">Quick actions</h2>

                <div class="section-sub mb-3">
                    Common administration tasks
                </div>

                <div class="d-grid gap-2">

                    <a
                        href="<?php echo e(route('institutions.create')); ?>"
                        class="quick-action"
                    >
                        <i class="bi bi-building-add"></i>

                        <span>
                            <strong>Add an institution</strong>
                            <small>
                                Register a new school or organization
                            </small>
                        </span>

                        <i
                            class="bi bi-chevron-right
                            ms-auto text-secondary"
                        ></i>
                    </a>

                    <a
                        href="<?php echo e(route('institutions.index')); ?>"
                        class="quick-action"
                    >
                        <i class="bi bi-list-check"></i>

                        <span>
                            <strong>Manage institutions</strong>
                            <small>
                                Search and update organization records
                            </small>
                        </span>

                        <i
                            class="bi bi-chevron-right
                            ms-auto text-secondary"
                        ></i>
                    </a>

                    <a
                        href="<?php echo e(route('profile.edit')); ?>"
                        class="quick-action"
                    >
                        <i class="bi bi-person-vcard"></i>

                        <span>
                            <strong>Account settings</strong>
                            <small>
                                Update your profile and password
                            </small>
                        </span>

                        <i
                            class="bi bi-chevron-right
                            ms-auto text-secondary"
                        ></i>
                    </a>

                </div>

                
                <div
                    class="mt-4 p-3 rounded-3"
                    style="background:#f7f9ff;border:1px solid #e8edff"
                >
                    <div class="d-flex gap-2">
                        <i
                            class="bi bi-shield-check fs-5"
                            style="color:#4263eb"
                        ></i>

                        <div>
                            <div class="fw-bold" style="font-size:12px">
                                Security reminder
                            </div>

                            <div class="muted mt-1" style="font-size:11px">
                                Grant users only the permissions required
                                for their responsibilities.
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>

    
    <div class="row g-3">
        <div class="col-12">
            <section class="card-pro table-card">
                <div
                    class="d-flex justify-content-between
                    align-items-start gap-2 mb-3"
                >
                    <div>
                        <h2 class="section-title">
                            Recently registered users
                        </h2>

                        <div class="section-sub">
                            Latest accounts created on EQBEMS
                        </div>
                    </div>

                    <span class="badge rounded-pill text-bg-light border px-3 py-2">
                        <?php echo e($userCount); ?> total
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table mb-0" id="recentUsersTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Registered</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div
                                            class="d-flex align-items-center gap-2"
                                        >
                                            <div
                                                class="avatar"
                                                style="width:32px;height:32px;font-size:12px"
                                            >
                                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                            </div>

                                            <span class="fw-bold">
                                                <?php echo e($user->name); ?>

                                            </span>
                                        </div>
                                    </td>

                                    <td><?php echo e($user->email); ?></td>

                                    <td>
                                        <?php echo e($user->created_at?->format('d M Y, h:i A') ?? '—'); ?>

                                    </td>

                                    <td>
                                        <span class="status-pill">
                                            Registered
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td
                                        colspan="4"
                                        class="text-center muted py-4"
                                    >
                                        No user accounts found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // User Growth Chart
            const labels = <?php echo json_encode($monthlyUsers->pluck('label'), 15, 512) ?>;
            const values = <?php echo json_encode($monthlyUsers->pluck('count'), 15, 512) ?>;

            const grid = '#edf0f6';
            const muted = '#8792a5';

            const growth = document.getElementById('userGrowthChart');

            if (growth) {
                new Chart(growth, {
                    type: 'line',

                    data: {
                        labels: labels,

                        datasets: [{
                            label: 'New users',
                            data: values,
                            borderColor: '#4263eb',
                            backgroundColor: 'rgba(66,99,235,.10)',
                            fill: true,
                            tension: 0.38,
                            borderWidth: 2.5,
                            pointRadius: 3,
                            pointBackgroundColor: '#4263eb'
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: {
                                backgroundColor: '#14213d',
                                padding: 12,
                                displayColors: false
                            }
                        },

                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },

                                ticks: {
                                    color: muted
                                }
                            },

                            y: {
                                beginAtZero: true,

                                grid: {
                                    color: grid
                                },

                                ticks: {
                                    color: muted,
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Institution Status Doughnut Chart
            const institution = document.getElementById('institutionChart');

            if (institution) {
                new Chart(institution, {
                    type: 'doughnut',

                    data: {
                        labels: ['Active', 'Inactive'],

                        datasets: [{
                            data: [
                                <?php echo e($activeInstitutions); ?>,
                                <?php echo e($inactiveInstitutions); ?>

                            ],

                            backgroundColor: [
                                '#4263eb',
                                '#e8edf5'
                            ],

                            borderWidth: 0,
                            hoverOffset: 5
                        }]
                    },

                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',

                        plugins: {
                            legend: {
                                display: false
                            },

                            tooltip: {
                                backgroundColor: '#14213d',
                                padding: 12
                            }
                        }
                    }
                });
            }

            // Recent Users DataTable
            const recentUsersTable = document.querySelector(
                '#recentUsersTable'
            );

            const hasEmptyMessage = document.querySelector(
                '#recentUsersTable tbody tr td[colspan]'
            );

            if (
                window.jQuery &&
                jQuery.fn.DataTable &&
                recentUsersTable &&
                !hasEmptyMessage
            ) {
                new DataTable('#recentUsersTable', {
                    responsive: true,
                    pageLength: 5,
                    lengthChange: false,
                    searching: false,
                    ordering: true,
                    info: false,

                    language: {
                        emptyTable: 'No users available'
                    }
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\srleo\Documents\eqbems\resources\views/dashboard/index.blade.php ENDPATH**/ ?>