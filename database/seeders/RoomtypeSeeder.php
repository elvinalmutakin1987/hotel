<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roomtype;

class RoomtypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Roomtype::insert(
            [
                [
                    'name' => 'Standart Room',
                    'price' => 250000
                ],
                [
                    'name' => 'Deluxe Room',
                    'price' => 350000
                ],
                [
                    'name' => 'Suite Room',
                    'price' => 500000
                ],
            ]
        );
    }
}
