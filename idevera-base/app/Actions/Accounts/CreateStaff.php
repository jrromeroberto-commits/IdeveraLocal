<?php

namespace App\Actions\Accounts;

use App\Enums\Role;
use App\Enums\StaffStatus;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CreateStaff
{
    /** @param list<string> $specialties */
    public function handle(User $actor, string $name, string $email, array $specialties): User
    {
        Gate::forUser($actor)->authorize('createStaff', User::class);

        return DB::transaction(function () use ($actor, $name, $email, $specialties): User {
            $user = new User;
            $user->forceFill(['name' => $name, 'email' => $email, 'password' => Str::password(32), 'role' => Role::STAFF])->save();
            $user->staffProfile()->update(['status' => StaffStatus::ACTIVE, 'specialties' => array_values(array_unique($specialties))]);
            AuditEvent::create(['actor_id' => $actor->id, 'target_user_id' => $user->id, 'event' => 'account.staff_created', 'metadata' => ['role' => Role::STAFF->value, 'specialties' => $specialties]]);

            return $user;
        });
    }
}
