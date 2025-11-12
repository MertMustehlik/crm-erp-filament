<?php

namespace App\Models;

use App\Models\InvoiceItem;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(InvoiceObserver::class)]
class Invoice extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'number',
        'type',
        'customer_id',
        'total',
        'vat_total',
        'grand_total',
        'invoice_date',
    ];

    public function casts(): array
    {
        return [
            'invoice_date' => 'datetime',
        ];
    }
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public static function generateNumber(): string
    {
        return date('Y') . str_pad(self::count() + 1, 4, '0', STR_PAD_LEFT);
    }
}
