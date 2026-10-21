<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // No default account or password is shipped. Explicitly opt into bootstrap.
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        if (! $email || ! $password) {
            $this->command?->warn('Admin not created. Set ADMIN_EMAIL and ADMIN_PASSWORD before seeding to bootstrap one.');
            return;
        }
        Validator::make(['email' => $email, 'password' => $password], [
            'email' => ['required','email'], 'password' => ['required','string','min:12'],
        ])->validate();
        $user = User::firstOrCreate(['email' => $email], [
            'name' => 'Platform Administrator', 'password' => Hash::make($password),
        ]);
        $user->assignRole('Super Admin');
    }
}
