<?php

namespace Tests\Feature;

use App\Actions\Content\PublishContent;
use App\Enums\ContentKind;
use App\Models\ContactMessage;
use App\Models\ContentEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicContentTest extends TestCase
{
    use RefreshDatabase;

    /** @return array<string, mixed> */
    private function data(string $title = 'Servicio publicado'): array
    {
        return ['kind' => 'SERVICE', 'title' => $title, 'slug' => 'servicio-demo', 'excerpt' => 'Introducción pública.', 'body' => 'Contenido del servicio.', 'art_style' => 'orbit', 'position' => 0, 'featured' => true, 'is_example' => true, 'service_ids' => []];
    }

    private function owner(): User
    {
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $this->actingAs($owner)->withSession(['auth.password_confirmed_at' => time()]);

        return $owner;
    }

    private function entry(): ContentEntry
    {
        return ContentEntry::create($this->data());
    }

    private function publish(ContentEntry $entry): void
    {
        app(PublishContent::class)->handle(null, $entry, $entry->lock_version, true);
        $entry->refresh();
    }

    public function test_only_owner_can_manage_content_and_media_even_with_nonexistent_ids(): void
    {
        foreach ([User::factory()->create(), User::factory()->staff()->create()] as $user) {
            $this->actingAs($user)->get('/owner/content')->assertForbidden();
            $this->postJson('/owner/content', $this->data())->assertForbidden();
            $this->postJson('/owner/content/999999', $this->data())->assertForbidden();
            $this->get('/owner/content/999999/media/999999')->assertForbidden();
            $this->get('/owner/contact')->assertForbidden();
            $this->get('/owner/content/settings')->assertForbidden();
        }
        $this->assertDatabaseCount('content_entries', 0);
    }

    public function test_cms_requires_owner_two_factor_and_publication_requires_recent_password(): void
    {
        $entry = $this->entry();
        $owner = User::factory()->owner()->create();
        $this->actingAs($owner)->postJson('/owner/content', $this->data())->assertStatus(423);
        $owner = User::factory()->owner()->withTwoFactor()->create();
        $this->actingAs($owner)->postJson('/owner/content/'.$entry->id.'/publication', ['publish' => true, 'lock_version' => 1])->assertStatus(423);
        $this->assertNull($entry->fresh()->published_at);
    }

    public function test_save_publish_edit_and_unpublish_keep_draft_separate_from_public_snapshot(): void
    {
        $this->owner();
        $this->post('/owner/content', $this->data())->assertSessionHasNoErrors();
        $entry = ContentEntry::query()->sole();
        $this->get('/servicios/'.$entry->slug)->assertNotFound();
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => true, 'lock_version' => 1])->assertSessionHasNoErrors();
        $this->get('/servicios/'.$entry->slug)->assertOk()->assertInertia(fn (Assert $page) => $page->where('entry.title', 'Servicio publicado')->missing('entry.published_snapshot'));
        $this->post('/owner/content/'.$entry->id, [...$this->data('Título privado en borrador'), 'lock_version' => 1])->assertSessionHasNoErrors();
        $this->get('/servicios/'.$entry->slug)->assertInertia(fn (Assert $page) => $page->where('entry.title', 'Servicio publicado'));
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => true, 'lock_version' => 2])->assertSessionHasNoErrors();
        $this->get('/')->assertInertia(fn (Assert $page) => $page->where('services.0.title', 'Título privado en borrador'));
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => false, 'lock_version' => 2])->assertSessionHasNoErrors();
        $this->get('/servicios/'.$entry->slug)->assertNotFound();
        $this->get('/')->assertInertia(fn (Assert $page) => $page->has('services', 0));
        $this->assertDatabaseHas('content_entries', ['id' => $entry->id, 'title' => 'Título privado en borrador', 'lock_version' => 3]);
    }

    public function test_stale_editor_cannot_overwrite_or_publish_newer_content(): void
    {
        $this->owner();
        $entry = $this->entry();
        $this->post('/owner/content/'.$entry->id, [...$this->data('Primera edición'), 'lock_version' => 1])->assertSessionHasNoErrors();
        $this->post('/owner/content/'.$entry->id, [...$this->data('Edición antigua'), 'lock_version' => 1])->assertSessionHasErrors('lock_version');
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => true, 'lock_version' => 1])->assertSessionHasErrors('lock_version');
        $this->assertSame('Primera edición', $entry->fresh()->title);
        $this->assertNull($entry->fresh()->published_at);
    }

    public function test_publication_retries_are_idempotent_and_retirement_blocks_old_publish_attempt(): void
    {
        $this->owner();
        $entry = $this->entry();
        $payload = ['publish' => true, 'lock_version' => 1];
        $this->post('/owner/content/'.$entry->id.'/publication', $payload)->assertSessionHasNoErrors();
        $this->post('/owner/content/'.$entry->id.'/publication', $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('content_changes', 1);
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => false, 'lock_version' => 1])->assertSessionHasNoErrors();
        $this->post('/owner/content/'.$entry->id.'/publication', $payload)->assertSessionHasErrors('lock_version');
        $this->assertNull($entry->fresh()->published_at);
    }

    public function test_drafts_are_absent_from_lists_filters_sitemap_and_related_services(): void
    {
        $draft = $this->entry();
        $case = ContentEntry::create([...$this->data('Caso conceptual'), 'kind' => ContentKind::PORTFOLIO, 'slug' => 'caso', 'service_ids' => [$draft->id]]);
        $this->publish($case);
        $this->get('/servicios')->assertInertia(fn (Assert $page) => $page->has('entries.data', 0));
        $this->get('/portfolio')->assertInertia(fn (Assert $page) => $page->has('filters', 0)->missing('entries.data.0.service_ids'));
        $this->get('/portfolio/caso')->assertInertia(fn (Assert $page) => $page->has('relatedServices', 0)->missing('entry.service_ids'));
        $this->get('/sitemap.xml')->assertDontSee('/servicios/servicio-demo')->assertSee('/portfolio/caso', false);
    }

    public function test_published_slug_and_sorting_are_preserved_while_editing_draft(): void
    {
        $this->owner();
        $entry = $this->entry();
        $this->publish($entry);
        $this->post('/owner/content/'.$entry->id, [...$this->data(), 'slug' => 'nueva-direccion', 'position' => 100, 'lock_version' => 1])->assertSessionHasNoErrors();
        $this->get('/servicios/servicio-demo')->assertOk()->assertInertia(fn (Assert $page) => $page->where('entry.position', 0));
        $this->get('/servicios/nueva-direccion')->assertNotFound();
        $this->get('/sitemap.xml')->assertDontSee('/servicios/nueva-direccion');
    }

    public function test_upload_stays_private_until_published_and_url_is_revoked_on_unpublish(): void
    {
        Storage::fake('local');
        Storage::fake('content_public');
        $this->owner();
        $image = UploadedFile::fake()->createWithContent('cover.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII='));
        $this->post('/owner/content', [...$this->data(), 'images' => [$image], 'image_alt' => 'Imagen propia para publicación'])->assertSessionHasNoErrors();
        $entry = ContentEntry::query()->sole();
        $media = $entry->media()->sole();
        Storage::disk('local')->assertExists($media->path);
        $this->assertSame([], Storage::disk('content_public')->allFiles());
        $this->get('/owner/content/'.$entry->id.'/media/'.$media->id)->assertOk();
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => true, 'lock_version' => 1])->assertSessionHasNoErrors();
        $snapshot = $entry->fresh()->publicData();
        $url = $snapshot['media'][0]['url'];
        $this->get($url)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->get('/servicios/servicio-demo')->assertInertia(fn (Assert $page) => $page->missing('entry.media.0.path')->missing('entry.media.0.token'));
        $this->post('/owner/content/'.$entry->id.'/publication', ['publish' => false, 'lock_version' => 1])->assertSessionHasNoErrors();
        $this->get($url)->assertNotFound();
        Storage::disk('local')->assertExists($media->path);
    }

    public function test_cross_content_media_and_svg_uploads_are_rejected(): void
    {
        Storage::fake('local');
        $this->owner();
        $first = $this->entry();
        $second = ContentEntry::create([...$this->data(), 'slug' => 'otro']);
        $media = $first->media()->create(['path' => 'content-drafts/test.png', 'alt' => 'Otro contenido', 'width' => 1, 'height' => 1]);
        $this->get('/owner/content/'.$second->id.'/media/'.$media->id)->assertNotFound();
        $this->post('/owner/content/'.$second->id, [...$this->data(), 'slug' => 'otro', 'lock_version' => 1, 'remove_media_ids' => [$media->id]])->assertSessionHasErrors('remove_media_ids.0');
        $svg = UploadedFile::fake()->createWithContent('script.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $this->post('/owner/content', [...$this->data(), 'slug' => 'svg', 'images' => [$svg], 'image_alt' => 'SVG no permitido'])->assertSessionHasErrors('images.0');
    }

    public function test_form_cannot_write_publication_fields_or_change_content_kind(): void
    {
        $this->owner();
        $entry = $this->entry();
        $this->post('/owner/content/'.$entry->id, [...$this->data(), 'kind' => 'PAGE', 'lock_version' => 1, 'published_snapshot' => ['title' => 'Inyectado']])->assertSessionHasErrors(['kind', 'published_snapshot']);
        $this->assertSame(ContentKind::SERVICE, $entry->fresh()->kind);
        $this->assertNull($entry->fresh()->published_at);
    }

    public function test_contact_is_persisted_private_validated_and_honeypot_is_ignored(): void
    {
        $payload = ['name' => 'Contacto de prueba', 'email' => 'contact@example.test', 'subject' => 'Consulta', 'message' => 'Quiero conversar sobre una idea.', 'privacy_acknowledged' => true, 'website' => ''];
        $this->post('/contacto', $payload)->assertRedirect('/contacto');
        $this->assertDatabaseCount('contact_messages', 1);
        $this->get('/contacto')->assertInertia(fn (Assert $page) => $page->missing('messages'));
        $this->post('/contacto', [...$payload, 'website' => 'spam'])->assertRedirect('/contacto');
        $this->assertDatabaseCount('contact_messages', 1);
        $this->post('/contacto', [...$payload, 'email' => 'incorrecto', 'privacy_acknowledged' => false])->assertSessionHasErrors(['email', 'privacy_acknowledged']);
        $owner = $this->owner();
        $this->get('/owner/contact')->assertInertia(fn (Assert $page) => $page->where('messages.data.0.email', 'contact@example.test')->where('unreadCount', 1));
        $message = ContactMessage::query()->sole();
        $this->post('/owner/contact/'.$message->id.'/read')->assertRedirect('/owner/contact');
        $this->assertNotNull($message->fresh()->read_at);
        $this->assertSame('OWNER', $owner->role->value);
    }

    public function test_contact_rate_limit_rejects_fourth_submission(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.47']);
        $data = ['name' => 'Test', 'email' => 'test@example.test', 'subject' => 'Idea', 'message' => 'Mensaje de prueba válido.', 'privacy_acknowledged' => true];
        for ($i = 0; $i < 3; $i++) {
            $this->post('/contacto', $data)->assertRedirect();
        }
        $this->post('/contacto', $data)->assertStatus(429);
        $this->assertDatabaseCount('contact_messages', 3);
    }

    public function test_service_selection_survives_navigation_and_is_available_to_client_dashboard(): void
    {
        $entry = $this->entry();
        $this->publish($entry);
        $this->get('/iniciar-proyecto?servicio=servicio-demo')->assertSessionHas('preferred_service_slug', 'servicio-demo');
        $this->actingAs(User::factory()->create())->get('/client')->assertInertia(fn (Assert $page) => $page->where('preferredServiceSlug', 'servicio-demo'));
        $this->get('/iniciar-proyecto')->assertInertia(fn (Assert $page) => $page->where('service.slug', 'servicio-demo'));
    }

    public function test_local_demo_is_idempotent_preserves_edits_and_is_disabled_in_production(): void
    {
        $this->artisan('idevera:content-demo')->assertSuccessful();
        $count = ContentEntry::query()->count();
        ContentEntry::query()->where('slug', 'orbit')->update(['title' => 'Edición del estudio']);
        $this->artisan('idevera:content-demo')->assertSuccessful();
        $this->assertDatabaseCount('content_entries', $count);
        $this->assertDatabaseHas('content_entries', ['slug' => 'orbit', 'title' => 'Edición del estudio']);
        app()->detectEnvironment(fn () => 'production');
        $this->artisan('idevera:content-demo')->assertFailed();
    }

    public function test_unknown_and_unpublished_pages_render_public_404_without_debug_details(): void
    {
        $this->get('/pagina-inexistente')->assertNotFound()->assertInertia(fn (Assert $page) => $page->component('public/error')->where('status', 404));
        $this->get('/servicios/no-publicado')->assertNotFound()->assertInertia(fn (Assert $page) => $page->component('public/error')->missing('exception'));
    }
}
