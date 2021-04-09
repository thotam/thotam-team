<?php

namespace Thotam\ThotamTeam\Database\Seeders;

use Illuminate\Database\Seeder;

class Team_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //PhanLoaiNhom Seed
        PhanLoaiNhom::updateOrCreate(
            ['id' => 1],
            [
                'name' => "Sản xuất",
                'tag' => "SX",
                'order' => 1,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 2],
            [
                'name' => "Văn phòng",
                'tag' => "VP",
                'order' => 2,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 3],
            [
                'name' => "Kinh doanh",
                'tag' => "KD",
                'order' => 3,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 4],
            [
                'name' => "Marketing",
                'tag' => "MKT",
                'order' => 4,
                'active' => true
            ]
        );
        PhanLoaiNhom::updateOrCreate(
            ['id' => 5],
            [
                'name' => "Thực tập sinh",
                'tag' => "TTS",
                'order' => 5,
                'active' => true
            ]
        );
    }
}
