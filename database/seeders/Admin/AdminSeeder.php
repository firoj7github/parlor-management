<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                'firstname'     => "Super",
                'lastname'      => "Admin",
                'username'      => "superadmin",
                'email'         => "superadmin@appdevs.net",
                'password'      => Hash::make("appdevs"),
                'created_at'    => now(),
                'status'        => true,
            ],
            [
                'firstname'     => "Ad",
                'lastname'      => "Min",
                'username'      => "admin",
                'email'         => "admin@appdevs.net",
                'password'      => Hash::make("appdevs"),
                'created_at'    => now(),
                'status'        => true,
            ]

        ];

        Admin::insert($data);

        // Admin::factory()->times(200)->create();

    }
}
