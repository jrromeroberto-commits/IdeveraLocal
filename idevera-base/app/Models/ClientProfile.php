<?php

namespace App\Models;

use App\Enums\ClientStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/** @property ClientStatus $status */
#[Fillable(['status'])]
class ClientProfile extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['status' => ClientStatus::class];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
