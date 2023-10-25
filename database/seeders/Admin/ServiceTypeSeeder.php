<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service_types = array(
            array('id' => '2','slug' => 'short-haircut','name' => 'Short Haircut','price' => '50.00000000','status' => '1','created_at' => '2023-10-25 06:38:08','updated_at' => '2023-10-25 06:38:08'),
            array('id' => '3','slug' => 'long-haircut','name' => 'Long Haircut','price' => '55.00000000','status' => '1','created_at' => '2023-10-25 06:38:25','updated_at' => '2023-10-25 06:38:25'),
            array('id' => '4','slug' => 'hair-wash','name' => 'Hair Wash','price' => '40.00000000','status' => '1','created_at' => '2023-10-25 06:38:38','updated_at' => '2023-10-25 06:38:38'),
            array('id' => '5','slug' => 'hair-color','name' => 'Hair Color','price' => '80.00000000','status' => '1','created_at' => '2023-10-25 06:38:51','updated_at' => '2023-10-25 06:38:51'),
            array('id' => '6','slug' => 'facial-massage','name' => 'Facial Massage','price' => '100.00000000','status' => '1','created_at' => '2023-10-25 06:39:08','updated_at' => '2023-10-25 06:39:08'),
            array('id' => '7','slug' => 'shaves-face','name' => 'Shaves Face','price' => '70.00000000','status' => '1','created_at' => '2023-10-25 06:39:18','updated_at' => '2023-10-25 06:39:18'),
            array('id' => '8','slug' => 'spa-health','name' => 'Spa & Health','price' => '120.00000000','status' => '1','created_at' => '2023-10-25 06:39:32','updated_at' => '2023-10-25 06:39:32'),
            array('id' => '9','slug' => 'lamination','name' => 'Lamination','price' => '60.00000000','status' => '1','created_at' => '2023-10-25 06:39:43','updated_at' => '2023-10-25 06:39:43')
        );
        ServiceType::insert($service_types);
    }
}
