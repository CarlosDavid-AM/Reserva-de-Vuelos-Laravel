<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the reservation into the expected API response format.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'passengers' => $this->passengers->map(fn ($passenger) => [
                'firstName' => $passenger->first_name,
                'lastName' => $passenger->last_name,
                'documentNumber' => $passenger->document_number,
                'documentType' => $passenger->document_type,
                'birthday' => $passenger->birthday->format('Y-m-d'),
            ]),
            'itinerary' => [
                'segment' => $this->segments->map(fn ($segment) => [
                    'origin' => $segment->origin,
                    'destination' => $segment->destination,
                    'departure' => $segment->departure->format('Y-m-d'),
                    'arrival' => $segment->arrival->format('Y-m-d'),
                    'carrier' => $segment->carrier,
                ]),
                'price' => [
                    'totalPrice' => (float) $this->total_price,
                    'totalTax' => (float) $this->total_tax,
                    'basePrice' => (float) $this->base_price,
                ],
            ],
        ];
    }
}
