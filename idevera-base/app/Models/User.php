<?php

namespace App\Models;

use App\Enums\ClientStatus;
use App\Enums\Role;
use App\Enums\StaffStatus;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property Role $role
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable;

    protected $attributes = ['role' => 'CLIENT'];

    protected static function booted(): void
    {
        static::created(function (User $user): void {
            if ($user->role === Role::CLIENT) {
                $user->clientProfile()->create([
                    'status' => $user->hasVerifiedEmail() ? ClientStatus::ACTIVE : ClientStatus::PENDING_VERIFICATION,
                ]);
            } elseif ($user->role === Role::STAFF) {
                $user->staffProfile()->create(['status' => StaffStatus::INACTIVE, 'specialties' => []]);
            }
        });

        static::updated(function (User $user): void {
            if ($user->role !== Role::CLIENT || ! $user->wasChanged('email_verified_at')) {
                return;
            }

            // Conditional update prevents verification from lifting a suspension.
            $user->clientProfile()->whereIn('status', [ClientStatus::ACTIVE, ClientStatus::PENDING_VERIFICATION])
                ->update(['status' => $user->hasVerifiedEmail() ? ClientStatus::ACTIVE : ClientStatus::PENDING_VERIFICATION]);
        });
    }

    /** @return HasOne<ClientProfile, $this> */
    public function clientProfile(): HasOne
    {
        return $this->hasOne(ClientProfile::class);
    }

    /** @return HasOne<StaffProfile, $this> */
    public function staffProfile(): HasOne
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function accountStatus(): ?string
    {
        return match ($this->role) {
            Role::CLIENT => $this->clientProfile()->first()?->status->value,
            Role::STAFF => $this->staffProfile()->first()?->status->value,
            Role::OWNER => 'ACTIVE',
        };
    }

    public function hasActiveAccount(): bool
    {
        return $this->hasVerifiedEmail() && $this->accountStatus() === 'ACTIVE';
    }

    public function isRestricted(): bool
    {
        return ! in_array($this->accountStatus(), ['ACTIVE', 'PENDING_VERIFICATION'], true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
