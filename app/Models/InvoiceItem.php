<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\Invoice;
use App\Models\Product;
use App\Observers\InvoiceItemObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(InvoiceItemObserver::class)]
class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'unit_id',
        'name',
        'quantity',
        'unit_name',
        'price',
        'vat_percent',
        'line_total',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
