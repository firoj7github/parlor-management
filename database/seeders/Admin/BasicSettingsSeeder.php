<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\BasicSettings;
use Illuminate\Database\Seeder;

class BasicSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'site_name'         => "eSalon",
            'site_title'        => "Parlour and Salon Booking Full Solution ",
            'base_color'        => "#cca876",
            'secondary_color'   => "rgba(204, 168, 118, 0.4)",
            'otp_exp_seconds'   => "3600",
            'timezone'          => "Asia/Dhaka",
            'user_registration'  => 1,
            'agree_policy'      => 1,
            'broadcast_config'  => [
                "method" => "pusher", 
                "app_id" => "1574360", 
                "primary_key" => "971ccaa6176db78407bf", 
                "secret_key" => "a30a6f1a61b97eb8225a", 
                "cluster" => "ap2" 
            ],
            'mail_config'       => [
                "method" => "smtp", 
                "host" => "appdevs.net",
                "port" => "465", 
                "encryption" => "ssl",
                "username" => "system@appdevs.net",
                "password" => "QP2fsLk?80Ac",
                "from" => "system@appdevs.net", 
                "app_name" => "eSalon",
            ],
            'push_notification_config'  => [
                "method" => "pusher", 
                "instance_id" => "fd7360fa-4df7-43b9-b1b5-5a40002250a1", 
                "primary_key" => "6EEDE8A79C61800340A87C89887AD14533A712E3AA087203423BF01569B13845"
            ],
            'email_verification'    => true,
            'site_logo_dark'        => 'seeder/logo.webp',
            'site_logo'             => 'seeder/logo.webp',
            'site_fav_dark'         => 'seeder/favicon.webp',
            'site_fav'              => 'seeder/favicon.webp',
            'web_version'           => '1.0.0',
        ];

        BasicSettings::firstOrCreate($data);
    }
}
