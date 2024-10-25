<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room::create([
            'room_number' => '101',
            'room_type'   => 'Single',
            'status'      => 'Available',
            'price'       => 100.00
        ]);

        Room::create([
            'room_number' => '102',
            'room_type'   => 'Double',
            'status'      => 'Occupied',
            'price'       => 150.00
        ]);

        Room::create([
            'room_number' => '103',
            'room_type'   => 'Suite',
            'status'      => 'Available',
            'price'       => 300.00
        ]);

        Room::create([
            'room_number' => '104',
            'room_type'   => 'Single',
            'status'      => 'Maintenance',
            'price'       => 100.00
        ]);
        Room::create([
            'room_number' => '105',
            'room_type'   => 'Single',
            'status'      => 'Available',
            'price'       => 100.00
        ]);
    }
}
