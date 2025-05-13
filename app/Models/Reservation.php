<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Reservation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'number',
        'check_in_date',
        'check_out_date',
        'price',
        'amount',
        'payment_status',
        'payment_method',
        'bank_name',
        'account_number',
        'card_holder_name',
        'transaction_id',
        'room_check_in',
        'room_check_out',
        'status'
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class)->withDefault(['number' => null]);
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class)->withDefault(['name' => null]);
    }

    public function reservationitem(): HasMany
    {
        return $this->hasMany(Reservationitem::class);
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
