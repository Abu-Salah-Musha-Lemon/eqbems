<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>

<div class="mb-4">
    <div class="eyebrow">System / Users</div>
    <h1 class="page-title">Edit User</h1>
    <p class="muted mb-0">
        Update <?php echo e($user->name); ?>'s account, roles, and permissions.
    </p>
</div>

<form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    
    <div class="card-pro p-4 p-lg-5 mb-4">
        <h2 class="section-title mb-4">Account Details</h2>

        <?php echo $__env->make('users._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="card-pro p-4 p-lg-5 mb-4">
        <h2 class="section-title mb-2">Role Assignment</h2>

        <p class="text-muted mb-4">
            Select one or more roles for this user.
        </p>

        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-lg-4 mb-3" wire:key="role-<?php echo e($role->id); ?>">
                    <label class="border rounded p-3 d-flex align-items-start gap-2 h-100">
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="<?php echo e($role->name); ?>"
                            class="form-check-input mt-1"
                            <?php if(in_array($role->name, old('roles', $userRoles))): echo 'checked'; endif; ?>
                        >

                        <span>
                            <strong><?php echo e($role->name); ?></strong>
                            <br>
                            <small class="text-muted">
                                Assign the <?php echo e($role->name); ?> role
                            </small>
                        </span>
                    </label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <p class="text-warning">
                        No roles found. Please create roles first.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <?php $__errorArgs = ['roles'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php $__errorArgs = ['roles.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="card-pro p-4 p-lg-5 mb-4">
        <h2 class="section-title mb-2">Permission Assignment</h2>

        <p class="text-muted mb-4">
            Select individual permissions for this user.
            These are direct permissions, separate from permissions inherited
            through the user's roles.
        </p>

        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-lg-4 mb-3" wire:key="permission-<?php echo e($permission->id); ?>">
                    <label class="border rounded p-3 d-flex align-items-center gap-2 h-100">
                        <input
                            type="checkbox"
                            name="permissions[]"
                            value="<?php echo e($permission->name); ?>"
                            class="form-check-input"
                            <?php if(in_array(
                                $permission->name,
                                old('permissions', $userPermissions)
                            )): echo 'checked'; endif; ?>
                        >

                        <span><?php echo e($permission->name); ?></span>
                    </label>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12">
                    <p class="text-warning">
                        No permissions found. Please create permissions first.
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <?php $__errorArgs = ['permissions.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-light">
            Cancel
        </a>

        <button type="submit" class="btn btn-primary">
            Save Changes
        </button>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\srleo\Documents\eqbems\resources\views/users/edit.blade.php ENDPATH**/ ?>