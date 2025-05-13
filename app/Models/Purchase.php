<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Purchase extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'supplier_id',
        'number',
        'date',
        'grand_total',
        'status'
    ];

    public function purchasedetail(): HasMany
    {
        return $this->hasMany(Purchasedetail::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault(['name' => null]);
    }

    public function goodreceipt(): HasMany
    {
        return $this->hasMany(Goodreceipt::class);
    }
}
