<?php $__env->startSection('title','User management'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <div class="eyebrow">System / Access</div>
        <h1 class="page-title">User management</h1>
        <p class="muted mb-0">Manage accounts that can access EQBEMS.</p>
    </div><a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary"><i class="bi bi-person-plus me-2"></i>Add
        user</a>
</div>
<div class="card-pro table-card">
    <div class="mb-3">
        <h2 class="section-title">All users</h2>
        <div class="section-sub"><?php echo e($users->total()); ?> accounts · Search, sort and manage access.</div>
    </div>
    <div class="table-responsive">
        <table class="table" id="usersTable">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar" style="width:34px;height:34px"><?php echo e(strtoupper(substr($user->name,0,1))); ?>

                            </div>
                            <div class="fw-bold"><?php echo e($user->name); ?> <?php if($user->id===auth()->id()): ?><span
                                    class="badge text-bg-light border">You</span><?php endif; ?></div>
                        </div>
                    </td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e($user->created_at?->format('d M Y') ?? '—'); ?></td>
                    <td>
                        <div class="d-flex gap-1"><a class="icon-btn" title="View"
                                href="<?php echo e(route('users.show',$user)); ?>"><i class="bi bi-eye"></i></a><a class="icon-btn"
                                title="Edit" href="<?php echo e(route('users.edit',$user)); ?>"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('users.destroy',$user)); ?>"><?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?><button class="icon-btn text-danger"
                                    data-confirm="This account will be deleted." type="submit" title="Delete"
                                    <?php echo e($user->id===auth()->id()?'disabled':''); ?>><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </td>
                </tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody>
        </table>
    </div>
    <div class="mt-3"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?><script>
    document.addEventListener('DOMContentLoaded', () => {
        const t = document.querySelector('#usersTable');
        if (t) new DataTable(t, {
            responsive: true,
            paging: false,
            info: false,
            language: {
                search: '',
                searchPlaceholder: 'Search users…'
            },
            columnDefs: [{
                targets: 3,
                orderable: false,
                searchable: false
            }]
        })
    });

</script><?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\srleo\Documents\eqbems\resources\views/users/index.blade.php ENDPATH**/ ?>