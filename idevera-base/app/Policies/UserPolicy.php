<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->role === Role::OWNER && $actor->hasActiveAccount();
    }

    public function view(User $actor, User $target): bool
    {
        return $actor->id === $target->id || $this->viewAny($actor);
    }

    public function manageAccess(User $actor, User $target): bool
    {
        return $this->viewAny($actor) && $actor->id !== $target->id && $target->role !== Role::OWNER;
    }

    public function createStaff(User $actor): bool
    {
        return $this->viewAny($actor);
    }

    public function deactivate(User $actor, User $target): bool
    {
        return $actor->id === $target->id && $actor->role !== Role::OWNER && ! $actor->isRestricted();
    }
}
