<?php

namespace App\Listeners;

use App\Events\InvoiceItemCreated;
use Illuminate\Support\Facades\Log;

class UpdateStockOnInvoiceItemCreated
{
    public function handle(InvoiceItemCreated $event): void
    {
        $invoiceItem = $event->invoiceItem;
        $quantity = $invoiceItem->quantity;
        $product = $invoiceItem->product;

        if (!$invoiceItem || $quantity <= 0 || !$product) return;

        Log::info("Yeni fatura kalemi eklendi. Ürün: {$product->name}. Stoktan düşülecek ürün adet sayısı: {$quantity}");
        $product->decreaseStock($quantity, [
            'reference' => $invoiceItem,
            'description' => 'Faturaya eklendi',
        ]);
    }
}
