<?php

namespace App\Http\Controllers\Owner;

use App\Actions\Content\PublishContent;
use App\Actions\Content\SaveContent;
use App\Enums\ContentKind;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\SaveContentRequest;
use App\Models\ContentChange;
use App\Models\ContentEntry;
use App\Models\ContentMedia;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContentController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', ContentEntry::class);
        $kind = ContentKind::tryFrom((string) $request->query('kind', 'SERVICE')) ?? ContentKind::SERVICE;

        return Inertia::render('owner/content/index', [
            'kind' => $kind->value,
            'entries' => ContentEntry::query()->where('kind', $kind)->orderBy('position')->orderBy('id')->paginate(20)->withQueryString()
                ->through(fn (ContentEntry $entry): array => $entry->only(['id', 'kind', 'title', 'slug', 'is_example', 'lock_version', 'published_at', 'published_version', 'updated_at'])),
        ]);
    }

    public function create(Request $request): Response
    {
        Gate::authorize('create', ContentEntry::class);

        return $this->editor(null, ContentKind::tryFrom((string) $request->query('kind')) ?? ContentKind::SERVICE);
    }

    public function edit(ContentEntry $entry): Response
    {
        Gate::authorize('update', $entry);

        return $this->editor($entry, $entry->kind);
    }

    private function editor(?ContentEntry $entry, ContentKind $kind): Response
    {
        return Inertia::render('owner/content/edit', [
            'entry' => $entry?->only(['id', 'kind', ...ContentEntry::EDITABLE, 'lock_version', 'published_at', 'published_version']),
            'kind' => $kind->value,
            'media' => $entry?->media()->where('active', true)->orderBy('id')->get(['id', 'alt', 'width', 'height'])->map(fn (ContentMedia $image): array => [...$image->toArray(), 'url' => '/owner/content/'.$entry->id.'/media/'.$image->id]) ?? [],
            'services' => ContentEntry::query()->where('kind', ContentKind::SERVICE)->orderBy('position')->get(['id', 'title', 'published_at']),
            'changes' => $entry ? ContentChange::query()->where('content_entry_id', $entry->id)->latest('id')->limit(10)->get(['id', 'event', 'metadata', 'created_at']) : [],
        ]);
    }

    public function store(SaveContentRequest $request, SaveContent $save): RedirectResponse
    {
        return $this->save($request, $save, null);
    }

    public function update(SaveContentRequest $request, ContentEntry $entry, SaveContent $save): RedirectResponse
    {
        return $this->save($request, $save, $entry);
    }

    private function save(SaveContentRequest $request, SaveContent $save, ?ContentEntry $entry): RedirectResponse
    {
        /** @var array<int, UploadedFile> $images */
        $images = $request->file('images', []);
        $record = $save->handle($request->user(), $entry, $request->validated(), $images);
        Inertia::flash('toast', ['type' => 'success', 'message' => 'Borrador guardado. Publica cuando esté listo.']);

        return to_route('owner.content.edit', $record);
    }

    public function publish(Request $request, ContentEntry $entry, PublishContent $publish): RedirectResponse
    {
        Gate::authorize('update', $entry);
        $data = $request->validate(['lock_version' => ['required', 'integer', 'min:1'], 'publish' => ['required', 'boolean']]);
        $publish->handle($request->user(), $entry, (int) $data['lock_version'], (bool) $data['publish']);
        Inertia::flash('toast', ['type' => 'success', 'message' => $data['publish'] ? 'Contenido publicado.' : 'Publicación retirada. El borrador se conserva.']);

        return to_route('owner.content.edit', $entry);
    }

    public function media(ContentEntry $entry, ContentMedia $media): StreamedResponse
    {
        Gate::authorize('view', $entry);
        abort_unless($media->content_entry_id === $entry->id && Storage::disk('local')->exists($media->path), 404);

        return Storage::disk('local')->response($media->path, null, ['Cache-Control' => 'no-store', 'X-Content-Type-Options' => 'nosniff']);
    }

    public function settings(): Response
    {
        Gate::authorize('viewAny', ContentEntry::class);

        return Inertia::render('owner/content/settings', ['settings' => SiteSetting::query()->where('key', 'studio')->first()?->only(['responsible_name', 'contact_email', 'contact_address', 'provisional_legal', 'lock_version']) ?? ['lock_version' => 1, 'provisional_legal' => true]]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        Gate::authorize('viewAny', ContentEntry::class);
        $data = $request->validate(['responsible_name' => ['required', 'string', 'max:120'], 'contact_email' => ['required', 'email', 'max:180'], 'contact_address' => ['nullable', 'string', 'max:250'], 'provisional_legal' => ['required', 'boolean'], 'lock_version' => ['required', 'integer', 'min:1']]);
        DB::transaction(function () use ($request, $data): void {
            SiteSetting::query()->firstOrCreate(['key' => 'studio']);
            $settings = SiteSetting::query()->where('key', 'studio')->lockForUpdate()->firstOrFail();
            if ($settings->lock_version !== (int) $data['lock_version']) {
                throw ValidationException::withMessages(['lock_version' => 'Los datos cambiaron. Copia tu edición antes de recargar.']);
            }
            $settings->fill($data);
            $settings->lock_version++;
            $settings->save();
            ContentChange::create(['actor_id' => $request->user()->id, 'event' => 'site.updated', 'metadata' => ['version' => $settings->lock_version]]);
        });
        Inertia::flash('toast', ['type' => 'success', 'message' => 'Datos públicos del estudio actualizados.']);

        return to_route('owner.content.settings');
    }
}
