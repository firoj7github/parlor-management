<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\ParlourHasService;
use App\Models\Admin\ParlourList;
use App\Models\Admin\ParlourListHasSchedule;
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
        
        //parlour

        $parlour_lists = array(
            array('area_id' => '1','slug' => 'e97ad214-6e5d-4e9b-bec6-e9ec4daaaf12','name' => 'Allure Salon and Spa','manager_name' => 'Master of the Evolve Salon','experience' => '1 Year','speciality' => 'Totam et lorem cillu','contact' => '4102416545','address' => '1204/RUAP, Log Angeles','off_days' => 'Friday,Saturday,Sunday,Monday','number_of_dates' => 5,'image' => 'seeder/parlour1.webp','status' => '1','created_at' => '2023-09-23 08:49:42','updated_at' => '2023-09-23 09:16:24'),

            array('area_id' => '2','slug' => '1be12a14-a8ef-4923-85d9-9ae72efcd5f8','name' => 'Evolve Salon','manager_name' => 'Master of the Evolve Salon','experience' => '2 Years','speciality' => 'Stylish Cutting','contact' => '0015332343','address' => '1204/RUAP, Log Angeles','off_days' => 'Saturday,Sunday,Monday','number_of_dates' => 6,'image' => 'seeder/parlour2.webp','status' => '1','created_at' => '2023-09-23 09:08:35','updated_at' => '2023-09-23 09:13:46'),

            array('area_id' => '2','slug' => '84aa94fd-4f9c-4637-960d-f6083485ecd8','name' => 'The Beauty Den','manager_name' => 'David Villa','experience' => '3 years','speciality' => 'Facial','contact' => '7441024587','address' => '225 8th Ave, New York, NY 199980, USA','off_days' => 'Tuesday,Wednesday,Thursday','number_of_dates' => 7,'image' => 'seeder/parlour3.webp','status' => '1','created_at' => '2023-09-23 09:20:16','updated_at' => '2023-09-23 09:20:16'),

            array('area_id' => '3','slug' => '82fd5767-0302-407b-b908-1f852c77f7e7','name' => 'Classy Cuts','manager_name' => 'Batista','experience' => '4 years','speciality' => 'Styling','contact' => '74102589632','address' => '225 8th Ave, New York, NY 199980, USA','off_days' => 'Friday,Saturday,Sunday,Monday,Tuesday','number_of_dates' => 7,'image' => 'seeder/parlour4.webp','status' => '1','created_at' => '2023-09-23 09:23:20','updated_at' => '2023-09-23 09:23:37'),

            array('area_id' => '4','slug' => 'f8fb5ce5-c9aa-4fc5-9471-17c66598cfe9','name' => 'GlamourGrove Salon','manager_name' => 'Angel Di Maria','experience' => '3 Years','speciality' => 'Mustache Trim','contact' => '014441252365','address' => '225 8th Ave, New York, NY 199980, USA','off_days' => 'Friday,Saturday,Sunday','number_of_dates' => 4,'image' => 'seeder/parlour5.webp','status' => '1','created_at' => '2023-09-23 09:29:19','updated_at' => '2023-09-23 09:29:19'),

            array('area_id' => '5','slug' => '672d793a-5812-4e63-bd7b-a775e3f50b10','name' => 'Elegance Euphoria','manager_name' => 'Jorge D Costa','experience' => '2 years','speciality' => 'Treatment','contact' => '12213321212','address' => '225 8th Ave, New York, NY 199980, USA','off_days' => 'Monday,Tuesday,Wednesday','number_of_dates' => 8,'image' => 'seeder/parlour6.webp','status' => '1','created_at' => '2023-09-23 09:35:42','updated_at' => '2023-09-23 09:35:54')
        );

        ParlourList::insert($parlour_lists);

        $parlour_has_services = array(
            array('parlour_list_id' => '1','service_name' => 'Lamination','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','service_name' => 'Spa & Health','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','service_name' => 'Shaves Face','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','service_name' => 'Long Haircut','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '2','service_name' => 'Facial Massage','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','service_name' => 'Spa & Health','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','service_name' => 'Shaves Face','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','service_name' => 'Hair Color','price' => '7.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','service_name' => 'Long Haircut','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','service_name' => 'Short Haircut','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
        
            array('parlour_list_id' => '3','service_name' => 'Facial Massage','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','service_name' => 'Spa & Health','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','service_name' => 'Shaves Face','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','service_name' => 'Hair Color','price' => '7.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','service_name' => 'Short Haircut','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '4','service_name' => 'Facial Massage','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','service_name' => 'Spa & Health','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','service_name' => 'Hair Color','price' => '7.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','service_name' => 'Short Haircut','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '5','service_name' => 'Facial Massage','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','service_name' => 'Spa & Health','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','service_name' => 'Shaves Face','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','service_name' => 'Hair Color','price' => '7.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','service_name' => 'Short Haircut','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '6','service_name' => 'Facial Massage','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','service_name' => 'Spa & Health','price' => '10.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','service_name' => 'Shaves Face','price' => '5.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','service_name' => 'Hair Color','price' => '7.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','service_name' => 'Short Haircut','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','service_name' => 'Lamination','price' => '6.00000000','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
        );

        ParlourHasService::insert($parlour_has_services);

        $parlour_list_has_schedules = array(
            array('parlour_list_id' => '1','from_time' => '10:00','to_time' => '11:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','from_time' => '11:00','to_time' => '12:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','from_time' => '14:00','to_time' => '15:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','from_time' => '15:00','to_time' => '16:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','from_time' => '17:00','to_time' => '18:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '1','from_time' => '18:00','to_time' => '20:00','max_client' => '4','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '2','from_time' => '10:00','to_time' => '11:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','from_time' => '11:00','to_time' => '12:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','from_time' => '14:00','to_time' => '15:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','from_time' => '15:00','to_time' => '16:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','from_time' => '17:00','to_time' => '18:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','from_time' => '18:00','to_time' => '20:00','max_client' => '4','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '2','from_time' => '20:00','to_time' => '21:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '3','from_time' => '10:00','to_time' => '11:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '11:00','to_time' => '12:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '14:00','to_time' => '15:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '15:00','to_time' => '16:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '17:00','to_time' => '18:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '18:00','to_time' => '20:00','max_client' => '4','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '20:00','to_time' => '21:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '21:00','to_time' => '22:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '4','from_time' => '10:00','to_time' => '11:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','from_time' => '11:00','to_time' => '12:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','from_time' => '14:00','to_time' => '15:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','from_time' => '15:00','to_time' => '16:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','from_time' => '18:00','to_time' => '20:00','max_client' => '4','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','from_time' => '20:00','to_time' => '21:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '4','from_time' => '21:00','to_time' => '22:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),

            array('parlour_list_id' => '5','from_time' => '10:00','to_time' => '11:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','from_time' => '11:00','to_time' => '12:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','from_time' => '14:00','to_time' => '15:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','from_time' => '15:00','to_time' => '16:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '3','from_time' => '17:00','to_time' => '18:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','from_time' => '18:00','to_time' => '20:00','max_client' => '4','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','from_time' => '20:00','to_time' => '21:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '5','from_time' => '21:00','to_time' => '22:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
        
            array('parlour_list_id' => '6','from_time' => '09:00','to_time' => '10:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '10:00','to_time' => '11:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '11:00','to_time' => '12:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '14:00','to_time' => '15:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '15:00','to_time' => '16:00','max_client' => '3','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '17:00','to_time' => '18:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '18:00','to_time' => '20:00','max_client' => '4','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '20:00','to_time' => '21:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
            array('parlour_list_id' => '6','from_time' => '21:00','to_time' => '22:00','max_client' => '2','status' => '1','created_at' => '2023-10-28 07:31:57','updated_at' => NULL),
        );
        
        ParlourListHasSchedule::insert($parlour_list_has_schedules);
    }
}
