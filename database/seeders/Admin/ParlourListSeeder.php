<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\Area;
use App\Models\Admin\ParlourList;
use App\Models\Admin\ParlourListHasSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParlourListSeeder extends Seeder
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

        //parlour

        $parlour_lists = array(
            array('area_id' => '1','slug' => 'e97ad214-6e5d-4e9b-bec6-e9ec4daaaf12','name' => 'Allure Salon and Spa','manager_name' => 'Master of the Evolve Salon','experience' => '1 Year','speciality' => 'Totam et lorem cillu','contact' => '4102416545','address' => '1204/RUAP, Log Angeles','price' => '25.00000000','off_days' => 'Friday,Saturday,Sunday,Monday','image' => 'seeder/parlour1.webp','status' => '1','created_at' => '2023-09-23 08:49:42','updated_at' => '2023-09-23 09:16:24'),
            array('area_id' => '2','slug' => '1be12a14-a8ef-4923-85d9-9ae72efcd5f8','name' => 'Evolve Salon','manager_name' => 'Master of the Evolve Salon','experience' => '2 Years','speciality' => 'Stylish Cutting','contact' => '0015332343','address' => '1204/RUAP, Log Angeles','price' => '309.00000000','off_days' => 'Saturday,Sunday,Monday','image' => 'seeder/parlour2.webp','status' => '1','created_at' => '2023-09-23 09:08:35','updated_at' => '2023-09-23 09:13:46'),
            array('area_id' => '2','slug' => '84aa94fd-4f9c-4637-960d-f6083485ecd8','name' => 'The Beauty Den','manager_name' => 'David Villa','experience' => '3 years','speciality' => 'Facial','contact' => '7441024587','address' => '225 8th Ave, New York, NY 199980, USA','price' => '40.00000000','off_days' => 'Tuesday,Wednesday,Thursday','image' => 'seeder/parlour3.webp','status' => '1','created_at' => '2023-09-23 09:20:16','updated_at' => '2023-09-23 09:20:16'),
            array('area_id' => '3','slug' => '82fd5767-0302-407b-b908-1f852c77f7e7','name' => 'Classy Cuts','manager_name' => 'Batista','experience' => '4 years','speciality' => 'Styling','contact' => '74102589632','address' => '225 8th Ave, New York, NY 199980, USA','price' => '35.00000000','off_days' => 'Friday,Saturday,Sunday,Monday,Tuesday','image' => 'seeder/parlour4.webp','status' => '1','created_at' => '2023-09-23 09:23:20','updated_at' => '2023-09-23 09:23:37'),
            array('area_id' => '4','slug' => 'f8fb5ce5-c9aa-4fc5-9471-17c66598cfe9','name' => 'GlamourGrove Salon','manager_name' => 'Angel Di Maria','experience' => '3 Years','speciality' => 'Mustache Trim','contact' => '014441252365','address' => '225 8th Ave, New York, NY 199980, USA','price' => '37.00000000','off_days' => 'Friday,Saturday,Sunday','image' => 'seeder/parlour5.webp','status' => '1','created_at' => '2023-09-23 09:29:19','updated_at' => '2023-09-23 09:29:19'),
            array('area_id' => '5','slug' => '672d793a-5812-4e63-bd7b-a775e3f50b10','name' => 'Elegance Euphoria','manager_name' => 'Jorge D Costa','experience' => '2 years','speciality' => 'Treatment','contact' => '12213321212','address' => '225 8th Ave, New York, NY 199980, USA','price' => '30.00000000','off_days' => 'Monday,Tuesday,Wednesday','image' => 'seeder/parlour6.webp','status' => '1','created_at' => '2023-09-23 09:35:42','updated_at' => '2023-09-23 09:35:54')
        );

        ParlourList::insert($parlour_lists);

        //parlourListHasSchedule
        $parlour_list_has_schedules = array(
            array('parlour_list_id' => '1','week_id' => '7','from_time' => '08:00','to_time' => '22:00','max_client' => '55','status' => '1','created_at' => '2023-09-23 09:13:46','updated_at' => NULL),
            array('parlour_list_id' => '1','week_id' => '6','from_time' => '08:00','to_time' => '22:00','max_client' => '55','status' => '1','created_at' => '2023-09-23 09:13:46','updated_at' => NULL),
            array('parlour_list_id' => '1','week_id' => '5','from_time' => '08:00','to_time' => '21:00','max_client' => '40','status' => '1','created_at' => '2023-09-23 09:13:46','updated_at' => NULL),
            array('parlour_list_id' => '1','week_id' => '4','from_time' => '09:00','to_time' => '19:00','max_client' => '35','status' => '1','created_at' => '2023-09-23 09:13:46','updated_at' => NULL),
            array('parlour_list_id' => '2','week_id' => '4','from_time' => '10:00','to_time' => '22:00','max_client' => '66','status' => '1','created_at' => '2023-09-23 09:16:24','updated_at' => NULL),
            array('parlour_list_id' => '2','week_id' => '5','from_time' => '06:00','to_time' => '20:00','max_client' => '70','status' => '1','created_at' => '2023-09-23 09:16:24','updated_at' => NULL),
            array('parlour_list_id' => '2','week_id' => '6','from_time' => '08:00','to_time' => '22:00','max_client' => '65','status' => '1','created_at' => '2023-09-23 09:16:24','updated_at' => NULL),
            array('parlour_list_id' => '3','week_id' => '7','from_time' => '08:00','to_time' => '22:00','max_client' => '55','status' => '1','created_at' => '2023-09-23 09:20:16','updated_at' => NULL),
            array('parlour_list_id' => '3','week_id' => '1','from_time' => '10:00','to_time' => '20:59','max_client' => '66','status' => '1','created_at' => '2023-09-23 09:20:16','updated_at' => NULL),
            array('parlour_list_id' => '3','week_id' => '2','from_time' => '11:00','to_time' => '20:00','max_client' => '55','status' => '1','created_at' => '2023-09-23 09:20:16','updated_at' => NULL),
            array('parlour_list_id' => '3','week_id' => '3','from_time' => '09:00','to_time' => '23:00','max_client' => '60','status' => '1','created_at' => '2023-09-23 09:20:16','updated_at' => NULL),
            array('parlour_list_id' => '4','week_id' => '5','from_time' => '10:00','to_time' => '22:00','max_client' => '45','status' => '1','created_at' => '2023-09-23 09:23:37','updated_at' => NULL),
            array('parlour_list_id' => '4','week_id' => '6','from_time' => '09:00','to_time' => '22:00','max_client' => '55','status' => '1','created_at' => '2023-09-23 09:23:37','updated_at' => NULL),
            array('parlour_list_id' => '5','week_id' => '3','from_time' => '11:00','to_time' => '12:00','max_client' => '45','status' => '1','created_at' => '2023-09-23 09:29:19','updated_at' => NULL),
            array('parlour_list_id' => '5','week_id' => '4','from_time' => '10:00','to_time' => '19:00','max_client' => '35','status' => '1','created_at' => '2023-09-23 09:29:19','updated_at' => NULL),
            array('parlour_list_id' => '5','week_id' => '5','from_time' => '00:00','to_time' => '20:00','max_client' => '25','status' => '1','created_at' => '2023-09-23 09:29:19','updated_at' => NULL),
            array('parlour_list_id' => '5','week_id' => '6','from_time' => '00:00','to_time' => '20:00','max_client' => '25','status' => '1','created_at' => '2023-09-23 09:29:19','updated_at' => NULL),
            array('parlour_list_id' => '6','week_id' => '6','from_time' => '10:00','to_time' => '23:00','max_client' => '35','status' => '1','created_at' => '2023-09-23 09:35:54','updated_at' => NULL),
            array('parlour_list_id' => '6','week_id' => '7','from_time' => '11:00','to_time' => '23:00','max_client' => '40','status' => '1','created_at' => '2023-09-23 09:35:54','updated_at' => NULL),
            array('parlour_list_id' => '6','week_id' => '1','from_time' => '10:00','to_time' => '23:00','max_client' => '25','status' => '1','created_at' => '2023-09-23 09:35:54','updated_at' => NULL),
            array('parlour_list_id' => '6','week_id' => '2','from_time' => '00:00','to_time' => '18:00','max_client' => '20','status' => '1','created_at' => '2023-09-23 09:35:54','updated_at' => NULL)
        );

        ParlourListHasSchedule::insert($parlour_list_has_schedules);
    }
}
