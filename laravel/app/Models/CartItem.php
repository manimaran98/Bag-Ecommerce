<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $table = 'cart_item';

    protected $primaryKey = 'cart_id';

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'id',
        'item_id',
        'item_img',
        'item_name',
        'item_price',
        'item_quantity',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'item_quantity' => 'integer',
            'item_price' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
