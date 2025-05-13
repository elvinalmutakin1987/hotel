<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Guest extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'name',
        'id_card_number',
        'address',
        'city',
        'phone'
    ];

    public function reservation(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
