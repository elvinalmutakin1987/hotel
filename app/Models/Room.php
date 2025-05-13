<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Room extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'number',
        'roomtype_id',
        'status',
    ];

    public function roomtype(): BelongsTo
    {
        return $this->belongsTo(Roomtype::class)->withDefault(['name' => null]);
    }

    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function checkin(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function checkout(): HasMany
    {
        return $this->hasMany(Checkout::class);
    }
}
