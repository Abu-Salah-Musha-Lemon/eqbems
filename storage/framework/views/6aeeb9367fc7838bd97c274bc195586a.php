<?php $__env->startSection('title','Institutions'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="eyebrow">Directory / Organizations</div>
        <h1 class="page-title">Institutions</h1>
        <p class="muted mb-0">Manage registered schools and education organizations.</p>
    </div><a href="<?php echo e(route('institutions.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-2"></i>Add
        institution</a>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
        <div class="card-pro p-3">
            <div class="muted fw-bold" style="font-size:10px">TOTAL INSTITUTIONS</div>
            <div class="stat-value mb-0"><?php echo e($institutions->total()); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card-pro p-3">
            <div class="muted fw-bold" style="font-size:10px">ACTIVE ON PAGE</div>
            <div class="stat-value mb-0"><?php echo e($institutions->getCollection()->where('status',true)->count()); ?></div>
        </div>
    </div>
</div>
<section class="card-pro table-card">
    <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
        <div>
            <h2 class="section-title">Institution directory</h2>
            <div class="section-sub">Search and manage institution records</div>
        </div>
        <div class="muted align-self-center" style="font-size:12px">
            <?php echo e($institutions->firstItem() ?? 0); ?>–<?php echo e($institutions->lastItem() ?? 0); ?> of <?php echo e($institutions->total()); ?>

        </div>
    </div>
    <div class="table-responsive">
        <table class="table" id="institutionsTable">
            <thead>
                <tr>
                    <th>Institution</th>
                    <th>Code</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $institutions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $institution): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="background:#fff2df;color:#a36b16;width:36px;height:36px"><i
                                    class="bi bi-building"></i></div>
                            <div>
                                <div class="fw-bold"><?php echo e($institution->name); ?></div>
                                <div class="muted" style="font-size:11px">
                                    <?php echo e($institution->email ?: 'No email provided'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge text-bg-light border"><?php echo e($institution->code); ?></span></td>
                    <td>
                        <div><?php echo e($institution->phone ?: '—'); ?></div>
                        <div class="muted" style="font-size:11px"><?php echo e($institution->website ?: ''); ?></div>
                    </td>
                    <td><?php echo e(collect([$institution->city,$institution->country])->filter()->implode(', ') ?: '—'); ?></td>
                    <td><span class="status-pill"
                            style="<?php echo e($institution->status ? '' : 'background:#fff0f0;color:#bd4242'); ?>"><?php echo e($institution->status ? 'Active' : 'Inactive'); ?></span>
                    </td>
                    <td class="text-nowrap"><?php echo e($institution->created_at?->format('d M Y') ?? '—'); ?></td>
                    <td>
                        <div class="d-flex gap-1"><a href="<?php echo e(route('institutions.show',$institution)); ?>"
                                class="icon-btn" title="View"><i class="bi bi-eye"></i></a><a
                                href="<?php echo e(route('institutions.edit',$institution)); ?>" class="icon-btn" title="Edit"><i
                                    class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('institutions.destroy',$institution)); ?>"><?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?><button type="submit" class="icon-btn text-danger" title="Delete"
                                    data-confirm="This institution will be moved to the trash."><i
                                        class="bi bi-trash3"></i></button></form>
                        </div>
                    </td>
                </tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr>
                    <td colspan="7" class="text-center py-5"><i
                            class="bi bi-buildings fs-2 d-block muted mb-2"></i><strong>No institutions found</strong>
                        <p class="muted mb-3 mt-1">Create your first institution to populate this directory.</p><a
                            class="btn btn-primary btn-sm" href="<?php echo e(route('institutions.create')); ?>">Add institution</a>
                    </td>
                </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3"><?php echo e($institutions->links()); ?></div>
</section>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?><script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.querySelector('#institutionsTable');
        if (table && table.querySelector('tbody tr td[colspan="7"]') === null) new DataTable(table, {
            responsive: true,
            pageLength: 10,
            searching: true,
            ordering: true,
            paging: false,
            info: false,
            language: {
                search: '',
                searchPlaceholder: 'Search institutions…'
            },
            columnDefs: [{
                targets: 6,
                orderable: false,
                searchable: false
            }]
        });
    });

</script><?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\srleo\Documents\eqbems\resources\views/institutions/index.blade.php ENDPATH**/ ?>