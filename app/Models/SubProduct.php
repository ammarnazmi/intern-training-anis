<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Onpay\Core\Eloquent\Concerns\SortStringResolver;
use Onpay\Core\Eloquent\Concerns\ValueSearcher;
class SubProduct extends Model
{

    use HasFactory, SortStringResolver, ValueSearcher;

    protected $fillable = [
        'product_id',
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
     * Get the product that owns the sub product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
