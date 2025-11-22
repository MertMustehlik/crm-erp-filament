<?php

namespace App\Events;

use App\Models\InvoiceItem;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvoiceItemDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(public InvoiceItem $oldInvoiceItem) {}
}
