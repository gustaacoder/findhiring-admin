<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@findhiring.local');

        User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'FindHiring Administrator',
                'password' => (string) env('ADMIN_PASSWORD', 'password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
