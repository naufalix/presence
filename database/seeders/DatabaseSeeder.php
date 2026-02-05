<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Office;
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

        Office::create([
            "name" => "Cubiconia Jakarta",  
            "address" => "Signature Park Grande CTB/L1/03, MT Haryono St No.Kav. 20, RT.4/RW.1, Cawang, Kramat Jati East Jakarta, 16360 Indonesia",  
            "hour_in" => "08:00:00",
            "hour_out" => "17:00:00",
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
