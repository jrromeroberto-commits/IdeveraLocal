<?php

namespace Tests\Feature;

use App\Actions\Content\PublishContent;
use App\Enums\ContentKind;
use App\Models\ContentEntry;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ContentSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_updates_public_contact_data_and_stale_updates_are_rejected(): void
    {
        $this->actingAs(User::factory()->owner()->withTwoFactor()->create())->withSession(['auth.password_confirmed_at' => time()]);
        $this->get('/owner/content/settings')->assertOk();
        $this->assertDatabaseCount('site_settings', 0);
        $data = ['responsible_name' => 'Persona de prueba', 'contact_email' => 'public@example.test', 'contact_address' => 'Dirección de prueba', 'provisional_legal' => true, 'lock_version' => 1];
        $this->post('/owner/content/settings', $data)->assertSessionHasNoErrors();
        $this->get('/contacto')->assertInertia(fn (Assert $page) => $page->where('site.contact_email', 'public@example.test')->missing('site.lock_version'));
        $this->post('/owner/content/settings', [...$data, 'contact_email' => 'stale@example.test'])->assertSessionHasErrors('lock_version');
        $this->assertSame('public@example.test', SiteSetting::query()->sole()->getAttribute('contact_email'));
        $this->assertDatabaseHas('content_changes', ['event' => 'site.updated']);
    }

    public function test_custom_pages_have_a_working_published_route(): void
    {
        $entry = ContentEntry::create(['kind' => ContentKind::PAGE, 'slug' => 'manifiesto', 'title' => 'Manifiesto', 'excerpt' => 'Ideas del estudio.', 'body' => 'Un texto propio.']);
        app(PublishContent::class)->handle(null, $entry, 1, true);
        $entry->refresh();
        $this->assertSame('/paginas/manifiesto', $entry->publicData()['url']);
        $this->get('/paginas/manifiesto')->assertOk()->assertInertia(fn (Assert $page) => $page->where('entry.title', 'Manifiesto'));
        $this->get('/sitemap.xml')->assertSee('/paginas/manifiesto', false);
    }

    public function test_real_portfolio_case_requires_a_selected_image_before_publication(): void
    {
        $this->actingAs(User::factory()->owner()->withTwoFactor()->create())->withSession(['auth.password_confirmed_at' => time()]);
        $entry = ContentEntry::create(['kind' => ContentKind::PORTFOLIO, 'slug' => 'real', 'title' => 'Caso', 'excerpt' => 'Una historia.', 'body' => 'Contenido propio.', 'is_example' => false]);
        $this->post('/owner/content/'.$entry->id.'/publication', ['lock_version' => 1, 'publish' => true])->assertSessionHasErrors('images');
        $this->assertNull($entry->fresh()->published_at);
    }

    public function test_indexable_public_pages_and_private_entry_points_have_distinct_metadata(): void
    {
        $this->get('/servicios')->assertOk()->assertDontSee('noindex,nofollow', false);
        $this->get('/login')->assertOk()->assertSee('noindex,nofollow', false);
        $this->get('/iniciar-proyecto')->assertOk()->assertSee('noindex,nofollow', false);
        $this->get('/sitemap.xml')->assertDontSee('/iniciar-proyecto', false)->assertDontSee('/login', false);
        $this->actingAs(User::factory()->owner()->withTwoFactor()->create());
        $this->get('/owner/content')->assertOk()->assertSee('noindex,nofollow', false);
    }
}
