<?php

namespace App\Actions\Accounts;

use App\Enums\ClientStatus;
use App\Enums\Role;
use App\Enums\StaffStatus;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class UpdateAccountAccess
{
    /** @param list<string> $specialties */
    public function handle(User $actor, User $target, string $status, array $specialties = []): void
    {
        DB::transaction(function () use ($actor, $target, $status, $specialties): void {
            $target = User::query()->lockForUpdate()->findOrFail($target->id);
            Gate::forUser($actor)->authorize('manageAccess', $target);

            $profile = $target->role === Role::CLIENT ? $target->clientProfile()->firstOrFail() : $target->staffProfile()->firstOrFail();
            $next = $target->role === Role::CLIENT ? ClientStatus::tryFrom($status) : StaffStatus::tryFrom($status);
            if (! $next || ($target->role === Role::CLIENT && (
                ($next === ClientStatus::ACTIVE && ! $target->hasVerifiedEmail()) ||
                ($next === ClientStatus::PENDING_VERIFICATION && $target->hasVerifiedEmail())
            ))) {
                throw ValidationException::withMessages(['status' => 'El estado no corresponde al rol o a la verificación de correo.']);
            }

            $before = ['status' => $profile->status->value];
            $after = ['status' => $next->value];
            if ($target->role === Role::STAFF) {
                $before['specialties'] = $target->staffProfile()->firstOrFail()->specialties;
                $after['specialties'] = array_values(array_unique($specialties));
            }
            if ($before === $after) {
                return;
            }

            $profile->update($after);
            if ($before['status'] !== $after['status']) {
                app(RevokeAccountSessions::class)->handle($target);
            }
            AuditEvent::create(['actor_id' => $actor->id, 'target_user_id' => $target->id, 'event' => 'account.access_updated', 'metadata' => ['before' => $before, 'after' => $after]]);
        });
    }
}
