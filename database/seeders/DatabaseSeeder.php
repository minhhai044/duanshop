<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'slug' => 'admin-user',
            'type' => \App\Models\User::TYPE_ADMIN,
            'is_active' => true,
        ]);

        // Tạo member user
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'slug' => 'test-user',
            'type' => \App\Models\User::TYPE_MEMBER,
            'is_active' => true,
        ]);
    }
}
