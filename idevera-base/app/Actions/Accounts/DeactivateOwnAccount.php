<?php

namespace App\Actions\Accounts;

use App\Enums\ClientStatus;
use App\Enums\Role;
use App\Enums\StaffStatus;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DeactivateOwnAccount
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user = User::query()->lockForUpdate()->findOrFail($user->id);
            Gate::forUser($user)->authorize('deactivate', $user);
            $before = $user->accountStatus();
            $status = $user->role === Role::CLIENT ? ClientStatus::DEACTIVATED->value : StaffStatus::INACTIVE->value;
            $profile = $user->role === Role::CLIENT ? $user->clientProfile()->firstOrFail() : $user->staffProfile()->firstOrFail();
            $profile->update(['status' => $status]);
            app(RevokeAccountSessions::class)->handle($user);
            AuditEvent::create(['actor_id' => $user->id, 'target_user_id' => $user->id, 'event' => 'account.deactivated', 'metadata' => ['before' => $before, 'after' => $status]]);
        });
    }
}
