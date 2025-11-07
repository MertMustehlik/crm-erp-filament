<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Customer;

class CustomerStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'status_id');
    }
}
