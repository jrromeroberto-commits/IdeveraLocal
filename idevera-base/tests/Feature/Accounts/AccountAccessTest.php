<?php

namespace Tests\Feature\Accounts;

use App\Enums\ClientStatus;
use App\Enums\Role;
use App\Enums\StaffStatus;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class AccountAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_registration_creates_only_a_pending_client(): void
    {
        $this->post(route('register.store'), ['name' => 'Cliente', 'email' => 'client@example.test', 'password' => 'password', 'password_confirmation' => 'password'])
            ->assertRedirect(route('dashboard'));
        $user = User::query()->sole();
        $this->assertSame(Role::CLIENT, $user->role);
        $this->assertSame('PENDING_VERIFICATION', $user->accountStatus());
        $this->assertDatabaseCount('staff_profiles', 0);
        $this->get(route('client.dashboard'))->assertRedirect(route('verification.notice'));
    }

    public function test_public_registration_rejects_privileged_fields(): void
    {
        foreach (['role' => 'OWNER', 'status' => 'ACTIVE', 'specialties' => ['DEVELOPER']] as $field => $value) {
            $this->post(route('register.store'), ['name' => 'Intruso', 'email' => 'intruder@example.test', 'password' => 'password', 'password_confirmation' => 'password', $field => $value])
                ->assertSessionHasErrors($field);
        }
        $this->assertDatabaseCount('users', 0);
    }

    public function test_profile_updates_cannot_change_privileges_or_verification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch(route('profile.update'), ['name' => $user->name, 'email' => $user->email, 'role' => 'OWNER', 'status' => 'ACTIVE', 'email_verified_at' => now()->toDateTimeString()])
            ->assertSessionHasErrors(['role', 'status', 'email_verified_at']);
        $this->assertSame(Role::CLIENT, $user->fresh()->role);
    }

    public function test_each_role_has_a_separate_area_and_other_roles_cannot_enter(): void
    {
        $users = [User::factory()->create(), User::factory()->staff()->create(), User::factory()->owner()->withTwoFactor()->create()];
        foreach ($users as $user) {
            $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route($user->role->dashboardRoute()));
            foreach (Role::cases() as $role) {
                $response = $this->get(route($role->dashboardRoute()));
                $role === $user->role ? $response->assertOk() : $response->assertForbidden();
            }
        }
    }

    public function test_clients_and_staff_cannot_list_accounts_or_mutate_another_account(): void
    {
        $target = User::factory()->create();
        foreach ([User::factory()->create(), User::factory()->staff()->create()] as $actor) {
            $this->actingAs($actor)->get(route('owner.accounts.index'))->assertForbidden();
            $this->patch(route('owner.accounts.update', $target), ['status' => 'SUSPENDED'])->assertForbidden();
            $this->patch(route('owner.accounts.update', 999999), ['status' => 'SUSPENDED'])->assertForbidden();
            $this->post(route('owner.accounts.store'), ['name' => 'New', 'email' => 'new@example.test', 'specialties' => []])->assertForbidden();
            $this->assertFalse(Gate::forUser($actor)->allows('view', $target));
        }
        $this->assertSame('ACTIVE', $target->accountStatus());
        $this->assertDatabaseCount('audit_events', 0);
    }

    public function test_owner_must_have_confirmed_two_factor_authentication(): void
    {
        $owner = User::factory()->owner()->create();
        $this->actingAs($owner)->get(route('owner.accounts.index'))->assertRedirect(route('security.edit'));
        $this->postJson(route('owner.accounts.store'), ['name' => 'Staff', 'email' => 'staff@example.test', 'specialties' => []])->assertStatus(423);
        $owner->forceFill(['two_factor_secret' => encrypt('secret')])->save();
        $this->get(route('owner.dashboard'))->assertRedirect(route('security.edit'));
        $this->assertDatabaseCount('staff_profiles', 0);
    }

    public function test_owner_mutations_require_recent_password_confirmation(): void
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $target = User::factory()->create();
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time() - 901])
            ->patchJson(route('owner.accounts.update', $target), ['status' => 'SUSPENDED'])->assertStatus(423);
        $this->assertSame('ACTIVE', $target->accountStatus());
        $this->assertDatabaseCount('audit_events', 0);
    }

    public function test_owner_can_create_staff_with_independent_specialties_and_no_public_password(): void
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time()])
            ->post(route('owner.accounts.store'), ['name' => 'Diseñadora', 'email' => 'staff@example.test', 'specialties' => ['DESIGNER', 'ANIMATOR']])
            ->assertSessionHasNoErrors()->assertRedirect(route('owner.accounts.index'));
        $staff = User::query()->where('email', 'staff@example.test')->firstOrFail();
        $this->assertSame(Role::STAFF, $staff->role);
        $this->assertFalse($staff->hasVerifiedEmail());
        $this->assertSame(['DESIGNER', 'ANIMATOR'], $staff->staffProfile()->firstOrFail()->specialties);
        $this->assertFalse($staff->hasActiveAccount());
        $this->assertDatabaseHas('audit_events', ['event' => 'account.staff_created', 'target_user_id' => $staff->id, 'actor_id' => $owner->id]);
        $this->get(route('owner.accounts.index'))->assertInertia(fn (Assert $page) => $page->component('owner/accounts')->has('accounts.data', 1)->missing('accounts.data.0.password')->missing('auth.user.two_factor_secret'));
    }

    public function test_created_staff_can_choose_password_verify_email_and_enter_staff_area(): void
    {
        $staff = User::factory()->staff()->unverified()->create();
        $token = Password::createToken($staff);
        $this->post(route('password.update'), ['token' => $token, 'email' => $staff->email, 'password' => 'New-password-123!', 'password_confirmation' => 'New-password-123!'])->assertSessionHasNoErrors();
        $this->post(route('login.store'), ['email' => $staff->email, 'password' => 'New-password-123!'])->assertRedirect(route('dashboard'));
        $this->get(route('staff.dashboard'))->assertRedirect(route('verification.notice'));
        $this->get($this->verificationUrl($staff))->assertRedirect(route('staff.dashboard'));
        $this->get(route('staff.dashboard'))->assertOk();
    }

    public function test_admin_inputs_cannot_promote_a_user_or_invent_specialties(): void
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $staff = User::factory()->staff()->create();
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time()])
            ->patch(route('owner.accounts.update', $staff), ['status' => 'ACTIVE', 'specialties' => ['OWNER'], 'role' => 'OWNER'])->assertSessionHasErrors(['role', 'specialties.0']);
        $this->assertSame(Role::STAFF, $staff->fresh()->role);
        $this->assertDatabaseCount('audit_events', 0);
    }

    public function test_suspension_revokes_database_sessions_and_remember_token_and_is_audited_once(): void
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $client = User::factory()->create();
        $remember = $client->remember_token;
        DB::table('sessions')->insert(['id' => 'old-client-session', 'user_id' => $client->id, 'payload' => '', 'last_activity' => time()]);
        config(['session.driver' => 'database']);
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time()])
            ->patch(route('owner.accounts.update', $client), ['status' => 'SUSPENDED'])->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('sessions', ['id' => 'old-client-session']);
        $this->assertNotSame($remember, $client->fresh()->remember_token);
        $this->patch(route('owner.accounts.update', $client), ['status' => 'SUSPENDED'])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('audit_events', 1);
        $this->assertDatabaseHas('audit_events', ['actor_id' => $owner->id, 'target_user_id' => $client->id, 'event' => 'account.access_updated']);
        $this->actingAs($client)->get(route('client.dashboard'))->assertForbidden();
    }

    public function test_restrictions_apply_to_an_already_authenticated_user_and_allow_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get(route('client.dashboard'))->assertOk();
        $user->clientProfile()->update(['status' => ClientStatus::SUSPENDED]);
        $this->get(route('client.dashboard'))->assertForbidden()->assertInertia(fn (Assert $page) => $page->component('auth/account-restricted'));
        $this->patchJson(route('profile.update'), ['name' => 'Cambio', 'email' => $user->email])->assertForbidden();
        $this->get(route('home'))->assertOk();
        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_inactive_staff_and_deactivated_clients_cannot_enter_private_routes(): void
    {
        $staff = User::factory()->staff()->create();
        $staff->staffProfile()->update(['status' => StaffStatus::INACTIVE]);
        $client = User::factory()->create();
        $client->clientProfile()->update(['status' => ClientStatus::DEACTIVATED]);
        foreach ([$staff, $client] as $user) {
            $this->actingAs($user)->get(route('dashboard'))->assertForbidden();
            $this->get(route('profile.edit'))->assertForbidden();
        }
    }

    public function test_verification_activates_only_pending_clients_even_when_events_are_faked(): void
    {
        Event::fake([Verified::class]);
        foreach ([ClientStatus::PENDING_VERIFICATION, ClientStatus::SUSPENDED, ClientStatus::DEACTIVATED] as $status) {
            $user = User::factory()->unverified()->create();
            $user->clientProfile()->update(['status' => $status]);
            $this->actingAs($user)->get($this->verificationUrl($user))->assertRedirect();
            $this->assertTrue($user->fresh()->hasVerifiedEmail());
            $this->assertSame($status === ClientStatus::PENDING_VERIFICATION ? 'ACTIVE' : $status->value, $user->accountStatus());
        }
    }

    public function test_changing_email_returns_an_active_client_to_pending_verification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->patch(route('profile.update'), ['name' => $user->name, 'email' => 'changed@example.test'])->assertSessionHasNoErrors();
        $this->assertSame('PENDING_VERIFICATION', $user->accountStatus());
        $this->get(route('client.dashboard'))->assertRedirect(route('verification.notice'));
    }

    public function test_owner_cannot_activate_unverified_client_or_set_verified_client_to_pending(): void
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $pending = User::factory()->unverified()->create();
        $active = User::factory()->create();
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time()])
            ->patch(route('owner.accounts.update', $pending), ['status' => 'ACTIVE'])->assertSessionHasErrors('status');
        $this->patch(route('owner.accounts.update', $active), ['status' => 'PENDING_VERIFICATION'])->assertSessionHasErrors('status');
        $this->assertDatabaseCount('audit_events', 0);
    }

    public function test_owner_cannot_disable_self_or_another_owner(): void
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $otherOwner = User::factory()->owner()->withTwoFactor()->create();
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time()])
            ->patch(route('owner.accounts.update', $owner), ['status' => 'SUSPENDED'])->assertForbidden();
        $this->patch(route('owner.accounts.update', $otherOwner), ['status' => 'SUSPENDED'])->assertForbidden();
        $this->delete(route('profile.destroy'), ['password' => 'password'])->assertForbidden();
        $this->assertDatabaseCount('users', 2);
    }

    public function test_staff_self_deactivation_keeps_the_account_and_history(): void
    {
        $staff = User::factory()->staff()->create();
        $this->actingAs($staff)->delete(route('profile.destroy'), ['password' => 'password'])->assertRedirect(route('home'));
        $this->assertSame('INACTIVE', $staff->accountStatus());
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('audit_events', ['event' => 'account.deactivated', 'target_user_id' => $staff->id]);
        $this->assertGuest();
    }

    public function test_local_command_creates_first_owner_without_client_profile_and_requires_strong_secret(): void
    {
        $this->artisan('idevera:owner', ['email' => 'owner@example.test', '--name' => 'Owner'])
            ->expectsQuestion('Contraseña (12 caracteres, mayúsculas, minúsculas, números y símbolos)', 'Strong-password-123!')
            ->expectsQuestion('Confirma la contraseña', 'Strong-password-123!')->assertSuccessful();
        $owner = User::query()->sole();
        $this->assertSame(Role::OWNER, $owner->role);
        $this->assertTrue($owner->hasVerifiedEmail());
        $this->assertDatabaseCount('client_profiles', 0);
        $this->assertDatabaseHas('audit_events', ['event' => 'account.owner_provisioned', 'actor_id' => null]);
        $this->artisan('idevera:owner', ['email' => 'second@example.test'])->assertFailed();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_owner_command_does_not_promote_existing_accounts_or_accept_weak_passwords(): void
    {
        $client = User::factory()->create(['email' => 'client@example.test']);
        $this->artisan('idevera:owner', ['email' => $client->email, '--name' => 'Owner'])
            ->expectsQuestion('Contraseña (12 caracteres, mayúsculas, minúsculas, números y símbolos)', 'Strong-password-123!')
            ->expectsQuestion('Confirma la contraseña', 'Strong-password-123!')->assertFailed();
        $this->artisan('idevera:owner', ['email' => 'owner@example.test', '--name' => 'Owner'])
            ->expectsQuestion('Contraseña (12 caracteres, mayúsculas, minúsculas, números y símbolos)', 'password')
            ->expectsQuestion('Confirma la contraseña', 'password')->assertFailed();
        $this->assertSame(Role::CLIENT, $client->fresh()->role);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_owner_bootstrap_is_disabled_outside_local_or_testing(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->artisan('idevera:owner', ['email' => 'owner@example.test'])->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }

    private function verificationUrl(User $user): string
    {
        return URL::temporarySignedRoute('verification.verify', now()->addMinutes(30), ['id' => $user->id, 'hash' => sha1($user->email)]);
    }
}
