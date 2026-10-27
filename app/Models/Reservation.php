<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'total_price',
        'total_tax',
        'base_price',
    ];

    /**
     * Get the passengers associated with the reservation.
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Get the flight segments associated with the reservation.
     */
    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class);
    }
}
