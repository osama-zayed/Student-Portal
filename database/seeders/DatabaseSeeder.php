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
        department::create([
            "name"=>"hgjdfi",
            "phone_number"=>"775561590",
            
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123123123'),
            'user_type' => 'admin',
            "department_id" => "1",
        ]);
        
    }
}
