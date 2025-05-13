<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Reservationitem extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'additionalitem_id',
        'reservation_id',
        'price',
        'qty'
    ];

    protected $fillable = [
        'additionalitem_id',
        'reservation_id',
        'price',
        'qty'
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class)->withDefault(['number' => null]);
    }

    public function additionalitem(): BelongsTo
    {
        return $this->belongsTo(Additionalitem::class)->withDefault(['name' => null]);
    }
}
