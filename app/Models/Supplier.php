<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Supplier extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'name',
        'contact',
        'email',
        'address',
        'city',
        'tax_id'
    ];

    public function purchase(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function itemprice(): HasMany
    {
        return $this->hasMany(Itemprice::class);
    }
}
