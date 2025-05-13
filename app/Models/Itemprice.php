<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Itemprice extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'supplier_id',
        'item_id',
        'price',
        'unit',
    ];

    protected $fillable = [
        'supplier_id',
        'item_id',
        'price',
        'unit',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withDefault(['name' => null]);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault(['name' => null]);
    }
}
