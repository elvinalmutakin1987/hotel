<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkout extends Model
{
    use HasFactory;

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withDefault(['number' => null]);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class)->withDefault(['number' => null]);
    }
}
