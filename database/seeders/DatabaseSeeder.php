<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Office;
use App\Models\Presence;
use App\Models\User;
use File;
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
            "name" => "Naufal Ulinnuha",  
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
            "name" => "Naufal Ulinnuha",  
            "email" => "user@naufal.dev",  
            "email_verified_at" => now(),  
            "position" => "Web Developer",  
            "password" => bcrypt('user')
        ]);
        User::create([
            "name" => "Nada",  
            "email" => "nada@naufal.dev",  
            "email_verified_at" => now(),  
            "position" => "Project Manager",  
            "password" => bcrypt('nada')
        ]);
        User::create([
            "name" => "Stevia",  
            "email" => "stevia@naufal.dev",  
            "email_verified_at" => now(),  
            "position" => "Kucing",  
            "password" => bcrypt('stevia')
        ]);

        $presences = json_decode(File::get("database/data/presences.json"));
        foreach ($presences as $key => $value) {
            Presence::create([
                "user_id" => $value->user_id,
                "presence_date" => $value->presence_date,
                "time_in" => $value->time_in,
                "time_out" => $value->time_out,
                "location_in" => "-7.7654442,112.5906469",
                "location_out"  => "-8.1078426,112.92248",
                "image_in"  => "default.jpg",
                "image_out"  => "default.jpg",
                "status"  => 2,
            ]);
        }
    }
}
