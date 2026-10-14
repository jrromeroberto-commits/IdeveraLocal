<?php

namespace App\Models;

use App\Enums\ContentKind;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property ContentKind $kind
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property string $body
 * @property bool $is_example
 * @property int $lock_version
 * @property int|null $published_version
 * @property string|null $published_slug
 * @property CarbonImmutable|null $published_at
 * @property array<string, mixed>|null $published_snapshot
 */
#[Fillable(['kind', 'title', 'slug', 'excerpt', 'body', 'category', 'art_style', 'seo_title', 'seo_description', 'service_ids', 'position', 'featured', 'is_example'])]
class ContentEntry extends Model
{
    protected $attributes = ['lock_version' => 1, 'position' => 0, 'featured' => false, 'is_example' => true, 'art_style' => 'orbit'];

    public const EDITABLE = ['title', 'slug', 'excerpt', 'body', 'category', 'art_style', 'seo_title', 'seo_description', 'service_ids', 'position', 'featured', 'is_example'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['kind' => ContentKind::class, 'service_ids' => 'array', 'featured' => 'boolean', 'is_example' => 'boolean', 'published_snapshot' => 'array', 'published_at' => 'immutable_datetime', 'lock_version' => 'integer', 'published_version' => 'integer'];
    }

    /** @param Builder<ContentEntry> $query */
    public function scopePublished(Builder $query): void
    {
        $query->whereNotNull('published_at')->where('published_at', '<=', now())->whereNotNull('published_snapshot');
    }

    /** @return HasMany<ContentMedia, $this> */
    public function media(): HasMany
    {
        return $this->hasMany(ContentMedia::class);
    }

    /** @return array<string, mixed> */
    public function publicData(): array
    {
        $snapshot = $this->published_snapshot ?? [];
        $snapshot['id'] = $this->id;
        $snapshot['kind'] = $this->kind->value;
        $slug = $this->published_slug ?? '';
        $snapshot['url'] = $this->kind === ContentKind::PAGE
            ? (in_array($slug, ['about', 'proceso', 'contacto'], true) ? '/'.$slug : (str_starts_with($slug, 'legal-') ? '/legal/'.substr($slug, 6) : '/paginas/'.$slug))
            : $this->kind->path().$slug;
        $snapshot['media'] = array_map(fn (array $media): array => ['url' => '/media/'.$this->id.'/'.$media['token'], 'alt' => $media['alt'], 'width' => $media['width'], 'height' => $media['height']], $snapshot['media'] ?? []);
        unset($snapshot['service_ids']);

        return $snapshot;
    }
}
