<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@hotel.local')],
            [
                'name' => 'Administrador',
                'role' => UserRole::ADMIN->value,
                'active' => true,
                'password' => env('ADMIN_PASSWORD', 'Admin12345'),
            ]
        );
    }
}