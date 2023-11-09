<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppSettings;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'version'               => '1.0.0',
            'splash_screen_image'   => 'seeder/splash.webp'
        ];

        AppSettings::firstOrCreate($data);
    }
}
