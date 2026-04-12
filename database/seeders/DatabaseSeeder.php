<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Darnus',
            'email' => 'admin@darnusnews.com',
            'password' => bcrypt('admin123'),
        ]);

        \App\Models\Category::factory(5)->create();
        \App\Models\Region::factory(5)->create();
        \App\Models\Post::factory(30)->create();
    }
}
