<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockInventory extends Model
{
    protected $table = 'stock_inventory';

    protected $primaryKey = 'stock_id';

    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'stock_id';
    }

    /** @var list<string> */
    protected $fillable = [
        'stock_name',
        'stock_brand',
        'stock_category',
        'stock_quantity',
        'stock_description',
        'stock_img',
        'stock_price',
    ];

    protected function casts(): array
    {
        return [
            'stock_quantity' => 'integer',
            'stock_price' => 'integer',
        ];
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'item_id', 'stock_id');
    }
}
