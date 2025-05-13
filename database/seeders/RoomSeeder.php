<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Room::insert(
            [
                [
                    'roomtype_id' => 1,
                    'number' => '101'
                ],
                [
                    'roomtype_id' => 1,
                    'number' => '102'
                ],
                [
                    'roomtype_id' => 1,
                    'number' => '103'
                ],
                [
                    'roomtype_id' => 1,
                    'number' => '104'
                ],
                [
                    'roomtype_id' => 1,
                    'number' => '105'
                ]
            ]
        );
    }
}
