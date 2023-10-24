<?php

namespace Database\Seeders\Admin;

use App\Models\Admin\SetupSeo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetupSeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'slug'          => "lorem_ipsum",
            'title'         => "Lorem Ipsum is simply dummy text of the printing and typesetting industry.",
            'desc'          => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
            'tags'          => ['Lorem','Ipsum'],
            'last_edit_by'  => 1,
        ];

        SetupSeo::firstOrCreate($data);
    }
}
