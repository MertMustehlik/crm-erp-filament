<?php

namespace App\Observers;

use App\Models\InvoiceItem;

class InvoiceItemObserver
{
    public function creating(InvoiceItem $invoiceItem): void
    {
        $this->setProductName($invoiceItem);
        $this->setUnit($invoiceItem);
        $this->setLineTotal($invoiceItem);
    }

    public function created(InvoiceItem $invoiceItem): void
    {
        $this->setInvoiceTotals($invoiceItem);
    }

    public function updating(InvoiceItem $invoiceItem): void
    {
        $this->setProductName($invoiceItem);
        $this->setUnit($invoiceItem);
        $this->setLineTotal($invoiceItem);
    }

    public function updated(InvoiceItem $invoiceItem): void
    {
        $this->setInvoiceTotals($invoiceItem);
    }

    protected function setLineTotal(InvoiceItem $invoiceItem): void
    {
        $price = $invoiceItem?->price ?? 0;
        $quantity = $invoiceItem?->quantity ?? 0;
        $vatPercent = $invoiceItem?->vat_percent ?? 0;

        $subTotal = $price * $quantity;
        $vatAmount = $subTotal * ($vatPercent / 100);
        $lineTotal = $subTotal + $vatAmount;

        $invoiceItem->line_total = $lineTotal;
    }

    protected function setProductName(InvoiceItem $invoiceItem): void
    {
        $invoiceItem->name = $invoiceItem?->product?->name ?? null;
    }

    protected function setUnit(InvoiceItem $invoiceItem): void
    {
        $invoiceItem->unit_id = $invoiceItem?->product?->unit_id ?? null;

        $unitName = $invoiceItem?->product?->unit?->name ?? null;
        if ($unitName) {
            $invoiceItem->unit_name = $unitName;
        }
    }

    protected function setInvoiceTotals(InvoiceItem $invoiceItem): void
    {
        $invoice = $invoiceItem->invoice;

        $total = 0;
        $vatTotal = 0;
        $grandTotal = 0;

        foreach ($invoice->items as $item) {
            $price = $item->price ?? 0;
            $quantity = $item->quantity ?? 0;
            $vatPercent = $item->vat_percent ?? 0;

            $_total = $price * $quantity;
            $_vatTotal = $_total * ($vatPercent / 100);
            $_grandTotal = $_total + $_vatTotal;

            $total += $_total;
            $vatTotal += $_vatTotal;
            $grandTotal += $_grandTotal;
        }

        $invoice->total = $total;
        $invoice->vat_total = $vatTotal;
        $invoice->grand_total = $grandTotal;
        $invoice->save();
    }
}
