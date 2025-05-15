<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Invoicedetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'invoice_id',
        'item_id',
        'unit',
        'price',
        'discount',
        'after_discount',
        'tax',
        'qty',
        'sub_total'
    ];

    protected $fillable = [
        'invoice_id',
        'item_id',
        'unit',
        'price',
        'discount',
        'after_discount',
        'tax',
        'qty',
        'sub_total'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class)->withDefault(['number' => null]);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withDefault(['name' => null]);
    }
}
