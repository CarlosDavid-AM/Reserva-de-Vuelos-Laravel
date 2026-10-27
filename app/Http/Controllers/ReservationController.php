<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ReservationController extends Controller
{
    /**
     * Return all reservations with their passengers and segments.
     */
    public function index(): AnonymousResourceCollection
    {
        $reservations = Reservation::with(['passengers', 'segments'])->get();

        return ReservationResource::collection($reservations);
    }

    /**
     * Create a new reservation with its passengers and segments.
     */
    public function store(Request $request): ReservationResource
    {
        $validated = $request->validate([
            'itinerary.price.totalPrice' => ['required', 'numeric', 'min:0'],
            'itinerary.price.totalTax' => ['required', 'numeric', 'min:0'],
            'itinerary.price.basePrice' => ['required', 'numeric', 'min:0'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.firstName' => ['required', 'string'],
            'passengers.*.lastName' => ['required', 'string'],
            'passengers.*.documentNumber' => ['required', 'string'],
            'passengers.*.documentType' => ['required', 'string', 'in:DNI,PASSPORT,CC'],
            'passengers.*.birthday' => ['required', 'date_format:Y-m-d'],
            'itinerary.segment' => ['required', 'array', 'min:1'],
            'itinerary.segment.*.origin' => ['required', 'string', 'size:3'],
            'itinerary.segment.*.destination' => ['required', 'string', 'size:3'],
            'itinerary.segment.*.departure' => ['required', 'date_format:Y-m-d'],
            'itinerary.segment.*.arrival' => ['required', 'date_format:Y-m-d'],
            'itinerary.segment.*.carrier' => ['required', 'string', 'max:10'],
        ]);

        $reservation = Reservation::create([
            'total_price' => $validated['itinerary']['price']['totalPrice'],
            'total_tax' => $validated['itinerary']['price']['totalTax'],
            'base_price' => $validated['itinerary']['price']['basePrice'],
        ]);

        foreach ($validated['passengers'] as $passengerData) {
            $reservation->passengers()->create([
                'first_name' => $passengerData['firstName'],
                'last_name' => $passengerData['lastName'],
                'document_number' => $passengerData['documentNumber'],
                'document_type' => $passengerData['documentType'],
                'birthday' => $passengerData['birthday'],
            ]);
        }

        foreach ($validated['itinerary']['segment'] as $segmentData) {
            $reservation->segments()->create([
                'origin' => $segmentData['origin'],
                'destination' => $segmentData['destination'],
                'departure' => $segmentData['departure'],
                'arrival' => $segmentData['arrival'],
                'carrier' => $segmentData['carrier'],
            ]);
        }

        $reservation->load(['passengers', 'segments']);

        return new ReservationResource($reservation);
    }

    /**
     * Return a single reservation by ID.
     */
    public function show(Reservation $reservation): ReservationResource
    {
        $reservation->load(['passengers', 'segments']);

        return new ReservationResource($reservation);
    }

    /**
     * Update an existing reservation's passengers, segments, and price.
     */
    public function update(Request $request, Reservation $reservation): ReservationResource
    {
        $validated = $request->validate([
            'itinerary.price.totalPrice' => ['sometimes', 'numeric', 'min:0'],
            'itinerary.price.totalTax' => ['sometimes', 'numeric', 'min:0'],
            'itinerary.price.basePrice' => ['sometimes', 'numeric', 'min:0'],
            'passengers' => ['sometimes', 'array', 'min:1'],
            'passengers.*.firstName' => ['required_with:passengers', 'string'],
            'passengers.*.lastName' => ['required_with:passengers', 'string'],
            'passengers.*.documentNumber' => ['required_with:passengers', 'string'],
            'passengers.*.documentType' => ['required_with:passengers', 'string', 'in:DNI,PASSPORT,CC'],
            'passengers.*.birthday' => ['required_with:passengers', 'date_format:Y-m-d'],
            'itinerary.segment' => ['sometimes', 'array', 'min:1'],
            'itinerary.segment.*.origin' => ['required_with:itinerary.segment', 'string', 'size:3'],
            'itinerary.segment.*.destination' => ['required_with:itinerary.segment', 'string', 'size:3'],
            'itinerary.segment.*.departure' => ['required_with:itinerary.segment', 'date_format:Y-m-d'],
            'itinerary.segment.*.arrival' => ['required_with:itinerary.segment', 'date_format:Y-m-d'],
            'itinerary.segment.*.carrier' => ['required_with:itinerary.segment', 'string', 'max:10'],
        ]);

        if (isset($validated['itinerary']['price'])) {
            $reservation->update([
                'total_price' => $validated['itinerary']['price']['totalPrice'] ?? $reservation->total_price,
                'total_tax' => $validated['itinerary']['price']['totalTax'] ?? $reservation->total_tax,
                'base_price' => $validated['itinerary']['price']['basePrice'] ?? $reservation->base_price,
            ]);
        }

        if (isset($validated['passengers'])) {
            $reservation->passengers()->delete();
            foreach ($validated['passengers'] as $passengerData) {
                $reservation->passengers()->create([
                    'first_name' => $passengerData['firstName'],
                    'last_name' => $passengerData['lastName'],
                    'document_number' => $passengerData['documentNumber'],
                    'document_type' => $passengerData['documentType'],
                    'birthday' => $passengerData['birthday'],
                ]);
            }
        }

        if (isset($validated['itinerary']['segment'])) {
            $reservation->segments()->delete();
            foreach ($validated['itinerary']['segment'] as $segmentData) {
                $reservation->segments()->create([
                    'origin' => $segmentData['origin'],
                    'destination' => $segmentData['destination'],
                    'departure' => $segmentData['departure'],
                    'arrival' => $segmentData['arrival'],
                    'carrier' => $segmentData['carrier'],
                ]);
            }
        }

        $reservation->load(['passengers', 'segments']);

        return new ReservationResource($reservation);
    }

    /**
     * Delete a reservation and all its related records.
     */
    public function destroy(Reservation $reservation): Response
    {
        $reservation->delete();

        return response()->noContent();
    }
}
