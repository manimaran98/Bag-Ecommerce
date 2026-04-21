<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $table = 'purchase_item';

    protected $primaryKey = 'purchase_item_id';

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'purchase_id',
        'id',
        'stock_id',
        'stock_img',
        'stock_name',
        'stock_quantity',
        'stock_price',
        'purchase_date',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'stock_id' => 'integer',
            'stock_quantity' => 'integer',
            'purchase_date' => 'date',
        ];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
