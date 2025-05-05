<?php

namespace App\Traits;

use App\Models\User;

trait TransactionTrait
{
    public function checkBalance(User $user, $amount)
    {
        if ($user->balance < $amount) {
            return false;
        }
        return true;
    }
    public function deductBalance(User $user, $amount)
    {
        $user->withdraw($amount);
    }
    public function addBalance(User $user, $amount)
    {
        $user->deposit($amount); 
    }
}