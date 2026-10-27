<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Segment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Segment>
 */
class SegmentFactory extends Factory
{
    /**
     * Common airport IATA codes for generating realistic routes.
     *
     * @var list<string>
     */
    private array $airports = ['EZE', 'MIA', 'JFK', 'LAX', 'LHR', 'CDG', 'SCL', 'BOG', 'GRU', 'MEX'];

    /**
     * Common airline carriers.
     *
     * @var list<string>
     */
    private array $carriers = ['AA', 'LA', 'IB', 'BA', 'AF', 'UA', 'DL', 'AR'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departure = fake()->dateTimeBetween('+1 month', '+6 months');
        $arrival = clone $departure;
        $arrival->modify('+'.fake()->numberBetween(2, 12).' hours');

        $airports = fake()->randomElements($this->airports, 2, false);

        return [
            'reservation_id' => Reservation::factory(),
            'origin' => $airports[0],
            'destination' => $airports[1],
            'departure' => $departure->format('Y-m-d'),
            'arrival' => $arrival->format('Y-m-d'),
            'carrier' => fake()->randomElement($this->carriers),
        ];
    }
}
