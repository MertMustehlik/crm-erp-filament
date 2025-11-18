<?php

namespace App\Services;

use App\Models\Invoice;

class InvoiceTotalService
{
    public function updateTotals(Invoice $invoice): void
    {
        $total = 0;
        $vatTotal = 0;

        foreach ($invoice->items as $item) {
            $price = $item->price ?? 0;
            $quantity = $item->quantity ?? 0;
            $vatPercent = $item->vat_percent ?? 0;

            $_total = $price * $quantity;
            $_vatTotal = $_total * $vatPercent / 100;

            $total += $_total;
            $vatTotal += $_vatTotal;
        }

        $invoice->update([
            'total' => $total,
            'vat_total' => $vatTotal,
            'grand_total' => $total + $vatTotal
        ]);
    }
}
