<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use App\Models\StockMovement;

trait HasStock
{
     /*
     |--------------------------------------------------------------------------
     | Accessors
     |--------------------------------------------------------------------------
     */

    /**
     * Stock accessor.
     *
     * @return float
     */
    public function getStockAttribute(): float
    {
        return $this->stock();
    }

    /*
     |--------------------------------------------------------------------------
     | Methods
     |--------------------------------------------------------------------------
     */

    public function stock(): float
    {
        return (float) $this->stockMovements()->sum('amount');
    }

    public function increaseStock($amount = 1, $arguments = [])
    {
        return $this->createStockMovement(abs($amount), $arguments);
    }

    public function decreaseStock($amount = 1, $arguments = [])
    {
        return $this->createStockMovement(-1 * abs($amount), $arguments);
    }

    public function clearStock($newAmount = null, $arguments = [])
    {
        $this->stockMovements()->delete();

        if ($newAmount !== null) {
            $this->createStockMovement($newAmount, $arguments);
        }

        return true;
    }

    public function setStock($newAmount, $arguments = [])
    {
        $currentStock = $this->stock;

        if ($deltaStock = $newAmount - $currentStock) {
            return $this->createStockMovement($deltaStock, $arguments);
        }

        return true;
    }

    public function inStock($amount = 1): bool
    {
        return $this->stock > 0 && $this->stock >= $amount;
    }

    public function outOfStock(): bool
    {
        return $this->stock <= 0;
    }

    /**
     * Function to handle mutations (increase, decrease).
     *
     * @param  int $amount
     * @param  array  $arguments
     * @return bool
     */
    protected function createStockMovement($amount, $arguments = [])
    {
        $reference = Arr::get($arguments, 'reference');

        $createArguments = collect([
            'amount' => $amount,
            'description' => Arr::get($arguments, 'description'),
        ])->when($reference, function ($collection) use ($reference) {
            return $collection
                ->put('referenceable_type', $reference->getMorphClass())
                ->put('referenceable_id', $reference->getKey());
        })->toArray();

        return $this->stockMovements()->create($createArguments);
    }

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     */

    /* Stokta var olan ürünleri filtrele */
    public function scopeWhereInStock($query): mixed
    {
        return $query->where(function ($query) {
            return $query->whereHas('stockMovements', function ($query) {
                return $query->select('stockable_id')
                    ->groupBy('stockable_id')
                    ->havingRaw('SUM(amount) > 0');
            });
        });
    }

    /* Stokta olmayan ürünleri filtrele */
    public function scopeWhereOutOfStock($query): mixed
    {
        return $query->where(function ($query) {
            return $query->whereHas('stockMovements', function ($query) {
                return $query->select('stockable_id')
                    ->groupBy('stockable_id')
                    ->havingRaw('SUM(amount) <= 0');
            })->orWhereDoesntHave('stockMovements');
        });
    }

    /*
     |--------------------------------------------------------------------------
     | Relations
     |--------------------------------------------------------------------------
     */

    /**
     * Relation with StockMovement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function stockMovements(): mixed
    {
        return $this->morphMany(StockMovement::class, 'stockable');
    }
}
