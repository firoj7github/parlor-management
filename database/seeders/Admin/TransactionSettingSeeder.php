<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\TransactionSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $data = ['transfer' => 'Transfer Money Charges', 'exchange' => 'Exchange Money Charges','add' => 'Add Money Charge','out' => 'Money Out Charges'];
        $create = [];
        foreach($data as $slug => $item) {
            $create[] = [
                'admin_id'          => 1,
                'slug'              => $slug,
                'title'             => $item,
                'max_limit'         => 50000,
                'monthly_limit'     => 50000,
                'daily_limit'       => 5000,
            ];
        }
        TransactionSetting::insert($create);
    }
}
