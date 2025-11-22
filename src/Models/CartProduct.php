<?php

declare(strict_types=1);

namespace Molitor\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Molitor\Product\Models\Product;

class CartProduct extends Model
{
    protected $table = 'cart_products';

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        // Only if application has default User model
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
