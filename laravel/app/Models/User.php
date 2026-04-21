<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    /** @var list<string> */
    protected $fillable = [
        'username',
        'name',
        'address',
        'contact',
        'password',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $incrementing = true;

    public function getRememberTokenName(): ?string
    {
        return null;
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->username === config('halenmiaga.admin_username');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'id', 'id');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'id', 'id');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'id', 'id');
    }

    public function searchLogs(): HasMany
    {
        return $this->hasMany(UserSearchLog::class, 'user_id', 'id');
    }

    public function supportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class, 'user_id', 'id');
    }
}
