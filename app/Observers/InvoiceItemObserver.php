<?php

namespace App\Observers;

use App\Events\InvoiceItemCreated;
use App\Events\InvoiceItemUpdated;
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
        event(new InvoiceItemCreated($invoiceItem));
        app(InvoiceTotalService::class)->updateTotals($invoiceItem->invoice);
    }

    public function updating(InvoiceItem $invoiceItem): void
    {
        app(InvoiceItemService::class)->prepareItem($invoiceItem);
    }

    public function updated(InvoiceItem $invoiceItem): void
    {
        event(new InvoiceItemUpdated($invoiceItem, $invoiceItem->getOriginal('product_id'), $invoiceItem->getOriginal('quantity')));
        app(InvoiceTotalService::class)->updateTotals($invoiceItem->invoice);
    }

    public function deleted(InvoiceItem $invoiceItem): void
    {
        // @todo Şuan için fatura kalemi silindiğinde stok iadesi yapmıyoruz, eklenecek. !!
    }
}
