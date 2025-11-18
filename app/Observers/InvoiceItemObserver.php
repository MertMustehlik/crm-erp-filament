<?php

namespace App\Observers;

use App\Models\InvoiceItem;
use App\Services\InvoiceItemService;
use App\Services\InvoiceTotalService;

class InvoiceItemObserver
{
    public function creating(InvoiceItem $invoiceItem): void
    {
        app(InvoiceItemService::class)->prepareItem($invoiceItem);
    }

    public function created(InvoiceItem $invoiceItem): void
    {
        app(InvoiceTotalService::class)->updateTotals($invoiceItem->invoice);
    }

    public function updating(InvoiceItem $invoiceItem): void
    {
        app(InvoiceItemService::class)->prepareItem($invoiceItem);
    }

    public function updated(InvoiceItem $invoiceItem): void
    {
        app(InvoiceTotalService::class)->updateTotals($invoiceItem->invoice);
    }
}
