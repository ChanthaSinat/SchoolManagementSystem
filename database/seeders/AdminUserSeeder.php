<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('app.default_admin_email', 'admin@example.com');
        $password = config('app.default_admin_password', 'password');

        $admin = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]
        );

        // Ensure role column is set (for User model helpers like isAdmin())
        $admin->update(['role' => 'admin']);

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }
    }
}
