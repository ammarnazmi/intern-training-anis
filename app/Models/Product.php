<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Onpay\Core\Eloquent\Concerns\SortStringResolver;
use Onpay\Core\Eloquent\Concerns\ValueSearcher;

class Product extends Model
{
    use HasFactory, SortStringResolver, ValueSearcher;

    protected $fillable = [
        'name',
        'description',
        'price',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the sub products of the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\SubProduct, $this>
     */
    public function subProducts(): HasMany
    {
        return $this->hasMany(SubProduct::class);
    }
}
