<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'dashboard.view','user.view','user.create','user.edit','user.delete',
            'institution.view','institution.create','institution.update','institution.delete','institution.restore',
            'academic.view','academic.manage',
            'question.view','question.create','question.review',
            'blueprint.view','blueprint.manage','exam.view','exam.generate','exam.issue','exam.answers',
            'result.view','result.mark','result.publish',
        ] as $name) {
            Permission::findOrCreate($name, 'web');
        }
    }
}
