<?php

namespace App\Actions\Accounts;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RevokeAccountSessions
{
    public function handle(User $user): void
    {
        // The account middleware remains authoritative for every session driver.
        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $user->id)->delete();
        }
        $user->forceFill(['remember_token' => Str::random(60)])->save();
    }
}
