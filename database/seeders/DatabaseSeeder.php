<?php

namespace Database\Seeders;

use App\Models\Admin;
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

        Admin::create([
            "name" => "Naufal Ulinnuha (Admin)",  
            "email" => "admin@naufal.dev",  
            "password" => bcrypt('admin'),
            "role" => 'admin'
        ]);

        User::create([
            "name" => "Naufal Ulinnuha (Pegawai)",  
            "email" => "user@naufal.dev",  
            "email_verified_at" => now(),  
            "position" => "Web Developer",  
            "password" => bcrypt('user')
        ]);
    }
}
