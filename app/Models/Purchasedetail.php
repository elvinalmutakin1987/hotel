<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Purchasedetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'purchase_id',
        'item_id',
        'price',
        'qty',
        'sub_total'
    ];

    protected $fillable = [
        'purchase_id',
        'item_id',
        'qty',
        'price',
        'unit',
        'sub_total'
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class)->withDefault(['number' => null]);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withDefault(['name' => null]);
    }
}
