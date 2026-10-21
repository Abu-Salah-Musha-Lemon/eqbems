<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $all = Permission::where('guard_name', 'web')->pluck('name')->all();
        $matrix = [
            'Super Admin' => $all,
            'Organization Admin' => ['dashboard.view','institution.view','academic.view','academic.manage','question.view','question.create','question.review','blueprint.view','blueprint.manage','exam.view','exam.generate','result.view'],
            'Institution Admin' => ['dashboard.view','institution.view','academic.view','academic.manage','question.view','question.create','question.review','blueprint.view','blueprint.manage','exam.view','exam.generate','result.view'],
            'Teacher' => ['dashboard.view','academic.view','question.view','question.create','question.review','blueprint.view','exam.view','result.view','result.mark'],
            'Question Setter' => ['dashboard.view','academic.view','question.view','question.create'],
            'Moderator' => ['dashboard.view','academic.view','question.view','question.review','blueprint.view'],
            'Exam Controller' => ['dashboard.view','academic.view','question.view','question.create','blueprint.view','blueprint.manage','exam.view','exam.generate','question.review','exam.issue','exam.answers','result.view','result.mark','result.publish'],
            'Student' => ['dashboard.view'],
        ];
        foreach ($matrix as $role => $permissions) {
            Role::findOrCreate($role, 'web')->syncPermissions($permissions);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
