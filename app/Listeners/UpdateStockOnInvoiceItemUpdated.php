<?php

namespace App\Listeners;

use App\Events\InvoiceItemUpdated;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class UpdateStockOnInvoiceItemUpdated
{
    public function handle(InvoiceItemUpdated $event): void
    {
        $invoiceItem = $event->invoiceItem;
        $oldProductId = $event->oldProductId;
        $oldQuantity = $event->oldQuantity;

        // 1. Eğer ürün değiştiyse
        if ($oldProductId !== $invoiceItem->product_id) {
            $oldProduct = Product::find($oldProductId);

            // eski ürüne stok geri ekle
            Log::info("Faturadan ürün kaldırıldı. Ürün: {$oldProduct->name}. İade stok adeti: {$oldQuantity}");
            $oldProduct->increaseStock($oldQuantity, [
                'reference' => $invoiceItem,
                'description' => 'Faturadan ürün kaldırıldı.',
            ]);


            // yeni üründen yeni quantity düş
            Log::info("Yeni fatura kalemi eklendi. Ürün: {$invoiceItem->product->name}. Stoktan düşülecek ürün adet sayısı: {$invoiceItem->quantity}");
            $invoiceItem->product->decreaseStock($invoiceItem->quantity, [
                'description' => 'Faturaya eklendi',
                'reference' => $invoiceItem
            ]);
        }

        // 2. Eğer ürün değişmedi ama quantity değiştiyse
        elseif ($oldQuantity !== $invoiceItem->quantity) {
            $diff = $invoiceItem->quantity - $oldQuantity;

            if ($diff > 0) {
                Log::info("Fatura kalemi arttı. Ürün: {$invoiceItem->product->name}. Stoktan düşülecek ürün adet sayısı: {$diff}");
                $invoiceItem->product->decreaseStock($diff, [
                    'description' => 'Faturada ürün adeti arttırıldı.',
                    'reference' => $invoiceItem
                ]);
            } else {
                Log::info("Fatura kalemi azaltıldı. Ürün: {$invoiceItem->product->name}. Stoktan artacak ürün adet sayısı: {$diff}");
                $invoiceItem->product->increaseStock($diff, [
                    'description' => 'Faturada ürün adeti azaltıldı.',
                    'reference' => $invoiceItem
                ]);
            }
        }
    }
}
