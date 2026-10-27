<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $basePrice = fake()->randomFloat(2, 100, 1000);
        $totalTax = fake()->randomFloat(2, 10, 200);

        return [
            'total_price' => $basePrice + $totalTax,
            'total_tax' => $totalTax,
            'base_price' => $basePrice,
        ];
    }
}
