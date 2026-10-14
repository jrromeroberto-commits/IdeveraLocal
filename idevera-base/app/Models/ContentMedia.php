<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/** @property int $id
 * @property int $content_entry_id
 * @property string $path
 * @property string $alt
 * @property int $width
 * @property int $height
 */
#[Fillable(['content_entry_id', 'path', 'alt', 'width', 'height', 'active'])]
class ContentMedia extends Model
{
    protected $table = 'content_media';

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }
}
