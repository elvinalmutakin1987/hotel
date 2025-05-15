<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'supplier_id',
        'goodreceipt_id',
        'number',
        'supplier_bill',
        'date',
        'total',
        'discount',
        'after_discount',
        'tax',
        'grand_total',
        'status'
    ];

    public function invoicedetail(): HasMany
    {
        return $this->hasMany(Invoicedetail::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault(['name' => null]);
    }

    public function goodreceipt(): BelongsTo
    {
        return $this->belongsTo(Goodreceipt::class)->withDefault(['number' => null]);
    }
}
