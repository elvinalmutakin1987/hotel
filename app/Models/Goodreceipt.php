<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Goodreceipt extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'supplier_id',
        'purchase_id',
        'number',
        'date',
        'total',
        'discount',
        'after_discount',
        'tax',
        'grand_total',
        'status'
    ];

    public function goodreceiptdetail(): HasMany
    {
        return $this->hasMany(Goodreceiptdetail::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault(['name' => null]);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withDefault(['number' => null]);
    }
}
