<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Demo content only runs with APP_ENV=local; production and tests are untouched.
        // QuestionSeeder also calls SubjectSeeder to ensure its dependencies exist.
        if (app()->environment('local')) {
            $this->call(QuestionSeeder::class);
        }
    }
}
