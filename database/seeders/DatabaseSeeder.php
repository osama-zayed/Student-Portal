<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\department;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {       
        User::create([
            'name' => 'admin',
            'username' => 'admin@admin.com',
            'password' => bcrypt('123123123'),
            'user_type' => 'admin',
        ]);
        
    }
}
