<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Week;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('slug' => 'saturday',  'day' => 'Saturday',   'status' => '1', 'created_at' =>now()),
            array('slug' => 'sunday',    'day' => 'Sunday'   ,  'status' => '1', 'created_at' =>now()),
            array('slug' => 'monday',    'day' => 'Monday'   ,  'status' => '1', 'created_at' =>now()),
            array('slug' => 'tuesday',   'day' => 'Tuesday'   , 'status' => '1', 'created_at' =>now()),
            array('slug' => 'wednesday', 'day' => 'Wednesday' , 'status' => '1', 'created_at' =>now()),
            array('slug' => 'thursday',  'day' => 'Thursday'  , 'status' => '1', 'created_at' =>now()),
            array('slug' => 'friday',    'day' => 'Friday'   ,  'status' => '1', 'created_at' =>now()),   
        );
        Week::insert($data);
    }
}
