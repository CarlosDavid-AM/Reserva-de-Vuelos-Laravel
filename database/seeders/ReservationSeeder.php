<?php

namespace Database\Seeders;

use App\Models\Passenger;
use App\Models\Reservation;
use App\Models\Segment;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Seed 5 reservations, each with one passenger and one flight segment.
     */
    public function run(): void
    {
        Reservation::factory(5)
            ->has(Passenger::factory()->count(1))
            ->has(Segment::factory()->count(1))
            ->create();
    }
}
