<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Goodreceiptdetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'goodreceipt_id',
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
        'goodreceipt_id',
        'item_id',
        'unit',
        'price',
        'discount',
        'after_discount',
        'tax',
        'qty',
        'sub_total'
    ];

    public function goodreceipt(): BelongsTo
    {
        return $this->belongsTo(Goodreceipt::class)->withDefault(['number' => null]);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withDefault(['name' => null]);
    }
}
