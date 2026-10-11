<?php

namespace App\Models;

use App\Enums\StaffStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property StaffStatus $status
 * @property list<string> $specialties
 */
#[Fillable(['status', 'specialties'])]
class StaffProfile extends Model
{
    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['status' => StaffStatus::class, 'specialties' => 'array'];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
