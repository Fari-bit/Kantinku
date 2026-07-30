<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function update(User $user, Menu $menu): bool
    {
        return $user->id === $menu->penjual_id;
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->id === $menu->penjual_id;
    }
}
