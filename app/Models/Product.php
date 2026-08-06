<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationship: A product has many subproducts
    public function subproducts(): HasMany
    {
        return $this->hasMany(Subproduct::class);
    }
}
