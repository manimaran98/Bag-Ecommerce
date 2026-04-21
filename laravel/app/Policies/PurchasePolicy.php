<?php

namespace App\Policies;

use App\Models\Purchase;
use App\Models\User;

class PurchasePolicy
{
    public function view(User $user, Purchase $purchase): bool
    {
        return $user->isAdmin() || (int) $purchase->id === (int) $user->id;
    }
}
