<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\SetupKyc;

class SetupKycSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'slug'          => "user",
            'user_type'     => "USER",
            'status'        => true,
            'last_edit_by'  => 1,  
        ];

        SetupKyc::updateOrCreate($data);
    }
}
