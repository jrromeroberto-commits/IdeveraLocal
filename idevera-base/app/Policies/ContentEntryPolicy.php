<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\ContentEntry;
use App\Models\User;

class ContentEntryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === Role::OWNER && $user->hasActiveAccount();
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, ContentEntry $entry): bool
    {
        return $this->viewAny($user);
    }

    public function view(User $user, ContentEntry $entry): bool
    {
        return $this->viewAny($user);
    }
}
