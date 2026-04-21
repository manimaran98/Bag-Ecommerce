<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportRequest extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'body',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }
}
