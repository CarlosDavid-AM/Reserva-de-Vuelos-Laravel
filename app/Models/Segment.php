<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Segment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'reservation_id',
        'origin',
        'destination',
        'departure',
        'arrival',
        'carrier',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'departure' => 'date',
        'arrival' => 'date',
    ];

    /**
     * Get the reservation that owns this segment.
     */
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
