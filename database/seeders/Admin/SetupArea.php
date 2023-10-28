<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Area;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SetupArea extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = array(
            array('slug' => 'washington','name' => 'Washington','status' => '1','created_at' => '2023-09-23 10:42:41','updated_at' => '2023-09-23 10:42:41'),
            array('slug' => 'arizona','name' => 'Arizona','status' => '1','created_at' => '2023-09-23 10:42:50','updated_at' => '2023-09-23 10:42:50'),
            array('slug' => 'california','name' => 'California','status' => '1','created_at' => '2023-09-23 10:42:58','updated_at' => '2023-09-23 10:42:58'),
            array('slug' => 'chicago','name' => 'Chicago','status' => '1','created_at' => '2023-09-23 10:43:06','updated_at' => '2023-09-23 10:43:06'),
            array('slug' => 'texas','name' => 'Texas','status' => '1','created_at' => '2023-09-23 10:43:14','updated_at' => '2023-09-23 10:43:14'),
            array('slug' => 'san-francisco','name' => 'San Francisco','status' => '1','created_at' => '2023-09-23 10:43:23','updated_at' => '2023-09-23 10:43:23'),
            array('slug' => 'new-york-city','name' => 'New York City','status' => '1','created_at' => '2023-09-23 10:43:33','updated_at' => '2023-09-23 10:43:33'),
            array('slug' => 'florida','name' => 'Florida','status' => '1','created_at' => '2023-09-23 10:43:41','updated_at' => '2023-09-23 10:43:41')
        );
        Area::insert($areas);
    }
}
