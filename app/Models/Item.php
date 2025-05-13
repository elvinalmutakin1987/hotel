<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Item extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'item_code',
        'name',
        'unit',
        'stock',
    ];

    public function itemprice(): HasMany
    {
        return $this->hasMany(Itemprice::class);
    }

    public function purchasedetail(): HasMany
    {
        return $this->hasMany(Purchasedetail::class);
    }
}
