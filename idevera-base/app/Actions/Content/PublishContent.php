<?php

namespace App\Actions\Content;

use App\Enums\ContentKind;
use App\Models\ContentChange;
use App\Models\ContentEntry;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class PublishContent
{
    public function handle(?User $actor, ContentEntry $entry, int $version, bool $publish): void
    {
        $created = [];
        try {
            DB::transaction(function () use ($actor, $entry, $version, $publish, &$created): void {
                $record = ContentEntry::query()->lockForUpdate()->findOrFail($entry->id);
                if ($record->lock_version !== $version) {
                    throw ValidationException::withMessages(['lock_version' => 'El contenido cambió. Recarga antes de publicar.']);
                }
                if ($publish && $record->published_at && $record->published_version === $version) {
                    return;
                }
                if (! $publish && ! $record->published_at) {
                    return;
                }
                $oldMedia = $record->published_snapshot['media'] ?? [];
                if ($publish) {
                    $media = $record->media()->where('active', true)->orderBy('id')->get();
                    if ($record->kind === ContentKind::PORTFOLIO && ! $record->is_example && $media->isEmpty()) {
                        throw ValidationException::withMessages(['images' => 'Añade una imagen elegida expresamente para publicar este caso.']);
                    }
                    $snapshot = $record->only(ContentEntry::EDITABLE);
                    $snapshot['media'] = [];
                    foreach ($media as $image) {
                        $token = (string) Str::uuid();
                        $path = $token.'.'.pathinfo($image->path, PATHINFO_EXTENSION);
                        $stream = Storage::disk('local')->readStream($image->path);
                        if (! is_resource($stream)) {
                            throw new \RuntimeException('No se pudo leer la imagen del borrador.');
                        }
                        try {
                            Storage::disk('content_public')->put($path, $stream);
                        } finally {
                            fclose($stream);
                        }
                        $created[] = $path;
                        $snapshot['media'][] = ['token' => $token, 'path' => $path, 'alt' => $image->alt, 'width' => $image->width, 'height' => $image->height];
                    }
                    $record->published_snapshot = $snapshot;
                    $record->published_slug = $record->slug;
                    $record->published_at = now();
                    $record->published_version = $record->lock_version;
                    $record->setAttribute('published_position', $record->getAttribute('position'));
                    $record->setAttribute('published_featured', $record->getAttribute('featured'));
                } else {
                    $record->published_at = null;
                    $record->published_slug = null;
                    $record->published_snapshot = null;
                    $record->published_version = null;
                    $record->lock_version++;
                }
                $record->save();
                ContentChange::create(['actor_id' => $actor?->id, 'content_entry_id' => $record->id, 'event' => $publish ? 'content.published' : 'content.unpublished', 'metadata' => ['version' => $record->lock_version]]);
                DB::afterCommit(function () use ($oldMedia): void {
                    try {
                        Storage::disk('content_public')->delete(array_column($oldMedia, 'path'));
                    } catch (Throwable $cleanupError) {
                        report($cleanupError);
                    }
                });
            });
        } catch (Throwable $error) {
            Storage::disk('content_public')->delete($created);
            if ($error instanceof UniqueConstraintViolationException) {
                throw ValidationException::withMessages(['slug' => 'La dirección pública ya está ocupada por otro contenido.']);
            }
            throw $error;
        }
    }
}
