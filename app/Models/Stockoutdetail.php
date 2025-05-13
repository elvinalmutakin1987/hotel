<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Stockoutdetail extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'stockout_id',
        'item_id',
        'qty'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withDefault(['name' => null]);
    }

    public function stockout(): BelongsTo
    {
        return $this->belongsTo(Stockout::class)->withDefault(['number' => null]);
    }
}
