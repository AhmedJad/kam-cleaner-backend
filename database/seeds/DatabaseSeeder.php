<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "first_name" => "Admin",
            "last_name" => "Admin",
            "email" => "admin@ad.com",
            "password" => "123456",
            "type" => "Admin"
        ]);
    }
}
