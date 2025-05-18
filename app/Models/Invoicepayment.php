<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Invoicepayment extends Model implements Auditable
{
    use HasFactory;

    use \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'invoice_id',
        'supplier_id',
        'number',
        'date',
        'payment_method',
        'bank_name',
        'transaction_number',
        'payment_total',
        'status'
    ];

    protected $fillable = [
        'invoice_id',
        'supplier_id',
        'number',
        'date',
        'payment_method',
        'bank_name',
        'transaction_number',
        'payment_total',
        'status'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class)->withDefault(['number' => null]);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class)->withDefault(['name' => null]);
    }
}
