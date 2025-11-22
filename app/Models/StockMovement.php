<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    protected $fillable = [
        'stockable_type',
        'stockable_id',
        'referenceable_type',
        'referenceable_id',
        'amount',
        'description',
    ];

    public function stockable(): MorphTo
    {
        return $this->morphTo();
    }

    public function referenceable(): MorphTo
    {
        return $this->morphTo();
    }
}
