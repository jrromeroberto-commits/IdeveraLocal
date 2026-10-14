<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/** @property int $lock_version */
#[Fillable(['key', 'responsible_name', 'contact_email', 'contact_address', 'provisional_legal'])]
class SiteSetting extends Model
{
    protected $attributes = ['lock_version' => 1, 'provisional_legal' => true];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['provisional_legal' => 'boolean'];
    }

    /** @return array<string, mixed> */
    public static function publicData(): array
    {
        return self::query()->where('key', 'studio')->first()?->only(['responsible_name', 'contact_email', 'contact_address', 'provisional_legal']) ?? ['provisional_legal' => true];
    }
}
