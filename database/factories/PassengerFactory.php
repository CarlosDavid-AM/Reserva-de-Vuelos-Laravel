<?php

namespace Database\Factories;

use App\Models\Passenger;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Passenger>
 */
class PassengerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservation_id' => Reservation::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'document_number' => fake()->numerify('########'),
            'document_type' => fake()->randomElement(['DNI', 'PASSPORT', 'CC']),
            'birthday' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ];
    }
}
