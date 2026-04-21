<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $table = 'delivery';

    protected $primaryKey = 'delivery_id';

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'id',
        'purchase_id',
        'delivery_agent',
        'delivery_status',
        'address',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'purchase_id');
    }
}
