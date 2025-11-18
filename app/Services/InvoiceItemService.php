<?php

namespace App\Services;

use App\Models\InvoiceItem;

class InvoiceItemService
{
    public function prepareItem(InvoiceItem $invoiceItem): void
    {
        $product = $invoiceItem->product;

        $invoiceItem->name = $product?->name;
        $invoiceItem->unit_id = $product?->unit_id;
        $invoiceItem->unit_name = $product?->unit?->name;
        $invoiceItem->line_total = $this->calculateLineTotal($invoiceItem);
    }

    protected function calculateLineTotal(InvoiceItem $invoiceItem): float
    {
        $price = $invoiceItem?->price ?? 0;
        $quantity = $invoiceItem?->quantity ?? 0;
        $vatPercent = $invoiceItem?->vat_percent ?? 0;

        $subTotal = $price * $quantity;
        $vatAmount = $subTotal * $vatPercent / 100;

        return $subTotal + $vatAmount;
    }
}
