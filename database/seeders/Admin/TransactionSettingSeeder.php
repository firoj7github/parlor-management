<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\TransactionSetting;
use Illuminate\Database\Seeder;

class TransactionSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['parlour' => 'Parlour Booking Charges'];
        $create = [];
        foreach($data as $slug => $item) {
            $create[] = [
                'admin_id'          => 1,
                'slug'              => $slug,
                'title'             => $item,
                'fixed_charge'      => 2,
                'percent_charge'    => 1,
            ];
        }
        TransactionSetting::insert($create);
    }
}
