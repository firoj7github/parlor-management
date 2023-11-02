<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\AppOnboardScreens;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppOnboardScreenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app_onboard_screens = array(
            array('title' => 'The Professional Specialists in near by','sub_title' => 'Salons have a reputation for being a place where clients can go to get services done and be pampered in a peaceful environment.','image' => 'seeder/onboard1.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-11-02 05:23:08','updated_at' => '2023-11-02 05:23:08'),
            array('title' => 'Find near by Salons & book services','sub_title' => 'Salons have a reputation for being a place where clients can go to get services done and be pampered in a peaceful environment.','image' => 'seeder/onboard2.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-11-02 05:24:17','updated_at' => '2023-11-02 05:24:17'),
            array('title' => 'Style that fit your daily lifestyle','sub_title' => 'Salons have a reputation for being a place where clients can go to get services done and be pampered in a peaceful environment.','image' => 'seeder/onboard3.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-11-02 05:25:02','updated_at' => '2023-11-02 05:25:02'),
            array('title' => 'A whole new you','sub_title' => 'Salons have a reputation for being a place where clients can go to get services done and be pampered in a peaceful environment.','image' => 'seeder/onboard4.webp','status' => '1','last_edit_by' => '1','created_at' => '2023-11-02 05:25:39','updated_at' => '2023-11-02 05:25:39')
        );
        AppOnboardScreens::insert($app_onboard_screens);
    }
}
