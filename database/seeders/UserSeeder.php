<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 3 Admin
        User::factory()
            ->count(3)
            ->state([
                'role' => 'ADMIN',
            ])
            ->create();

        // 30 Customer
        User::factory()
            ->count(30)
            ->state([
                'role' => 'CUSTOMER',
            ])
            ->create();
    }
}
