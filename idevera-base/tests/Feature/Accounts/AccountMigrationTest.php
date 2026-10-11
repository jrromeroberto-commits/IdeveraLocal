<?php

namespace Tests\Feature\Accounts;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccountMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_verified_and_unverified_accounts_are_preserved_as_clients(): void
    {
        $active = User::factory()->create();
        $pending = User::factory()->unverified()->create();
        $before = DB::table('users')->get(['id', 'name', 'email', 'password', 'email_verified_at']);
        $migration = require database_path('migrations/2026_10_11_000000_add_account_roles_and_profiles.php');
        $migration->down();
        $migration->up();
        $this->assertEquals($before, DB::table('users')->get(['id', 'name', 'email', 'password', 'email_verified_at']));
        $this->assertDatabaseHas('users', ['id' => $active->id, 'role' => 'CLIENT']);
        $this->assertDatabaseHas('users', ['id' => $pending->id, 'role' => 'CLIENT']);
        $this->assertDatabaseHas('client_profiles', ['user_id' => $active->id, 'status' => 'ACTIVE']);
        $this->assertDatabaseHas('client_profiles', ['user_id' => $pending->id, 'status' => 'PENDING_VERIFICATION']);
        $this->assertDatabaseCount('staff_profiles', 0);
        $this->assertDatabaseCount('audit_events', 0);
    }
}
