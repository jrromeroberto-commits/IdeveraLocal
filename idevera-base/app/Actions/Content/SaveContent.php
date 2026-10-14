<?php

namespace App\Actions\Content;

use App\Enums\ContentKind;
use App\Models\ContentChange;
use App\Models\ContentEntry;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class SaveContent
{
    /** @param array<string, mixed> $data
     * @param  array<int, UploadedFile>  $images
     */
    public function handle(User $actor, ?ContentEntry $entry, array $data, array $images): ContentEntry
    {
        $paths = [];
        try {
            return DB::transaction(function () use ($actor, $entry, $data, $images, &$paths): ContentEntry {
                $record = $entry ? ContentEntry::query()->lockForUpdate()->findOrFail($entry->id) : new ContentEntry;
                if ($entry && $record->lock_version !== (int) $data['lock_version']) {
                    throw ValidationException::withMessages(['lock_version' => 'Hay una edición más reciente. Copia tu borrador antes de recargar.']);
                }
                if (! $entry) {
                    $record->kind = ContentKind::from($data['kind']);
                }
                $record->fill(Arr::only($data, ContentEntry::EDITABLE));
                $record->lock_version = $entry ? $record->lock_version + 1 : 1;
                $record->save();
                $remove = $data['remove_media_ids'] ?? [];
                $record->media()->whereIn('id', $remove)->update(['active' => false]);
                if ($record->media()->where('active', true)->count() + count($images) > 8) {
                    throw ValidationException::withMessages(['images' => 'Puedes mantener hasta ocho imágenes por contenido.']);
                }
                foreach ($data['media_alt'] ?? [] as $id => $alt) {
                    $record->media()->whereKey($id)->update(['alt' => $alt]);
                }
                foreach ($images as $image) {
                    $size = getimagesize($image->getRealPath());
                    if (! $size) {
                        throw ValidationException::withMessages(['images' => 'La imagen no se pudo leer.']);
                    }
                    $path = $image->store('content-drafts', 'local');
                    if (! $path) {
                        throw new \RuntimeException('No se pudo guardar la imagen.');
                    }
                    $paths[] = $path;
                    $record->media()->create(['path' => $path, 'alt' => $data['image_alt'], 'width' => $size[0], 'height' => $size[1], 'active' => true]);
                }
                ContentChange::create(['actor_id' => $actor->id, 'content_entry_id' => $record->id, 'event' => $entry ? 'draft.updated' : 'draft.created', 'metadata' => ['version' => $record->lock_version]]);

                return $record;
            });
        } catch (Throwable $error) {
            Storage::disk('local')->delete($paths);
            throw $error;
        }
    }
}
