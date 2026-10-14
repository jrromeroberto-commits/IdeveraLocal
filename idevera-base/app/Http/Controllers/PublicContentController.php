<?php

namespace App\Http\Controllers;

use App\Enums\ContentKind;
use App\Models\ContactMessage;
use App\Models\ContentEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PublicContentController extends Controller
{
    public function services(): Response
    {
        return $this->listing(ContentKind::SERVICE, 'Servicios', 'Diseño, tecnología y creatividad para dar forma a tu idea.');
    }

    public function portfolio(Request $request): Response
    {
        $query = ContentEntry::query()->published()->where('kind', ContentKind::PORTFOLIO);
        $selected = null;
        if ($request->filled('servicio')) {
            $service = ContentEntry::query()->published()->where('kind', ContentKind::SERVICE)->where('published_slug', $request->query('servicio'))->firstOrFail();
            $query->whereJsonContains('published_snapshot->service_ids', $service->id);
            $selected = $service->published_slug;
        }

        return Inertia::render('public/listing', ['kind' => 'PORTFOLIO', 'title' => 'Ideas que toman forma.', 'intro' => 'Explora las historias y exploraciones que compartimos contigo.', 'entries' => $query->orderBy('published_position')->orderBy('id')->paginate(12)->withQueryString()->through(fn (ContentEntry $entry): array => $entry->publicData()), 'filters' => ContentEntry::query()->published()->where('kind', ContentKind::SERVICE)->orderBy('published_position')->get()->map(fn (ContentEntry $entry): array => $entry->publicData()), 'selected' => $selected]);
    }

    private function listing(ContentKind $kind, string $title, string $intro): Response
    {
        return Inertia::render('public/listing', ['kind' => $kind->value, 'title' => $title, 'intro' => $intro, 'entries' => ContentEntry::query()->published()->where('kind', $kind)->orderBy('published_position')->orderBy('id')->paginate(12)->through(fn (ContentEntry $entry): array => $entry->publicData()), 'filters' => [], 'selected' => null]);
    }

    public function service(string $slug): Response
    {
        return $this->detail(ContentKind::SERVICE, $slug);
    }

    public function caseStudy(string $slug): Response
    {
        return $this->detail(ContentKind::PORTFOLIO, $slug);
    }

    public function about(): Response
    {
        return $this->detail(ContentKind::PAGE, 'about');
    }

    public function process(): Response
    {
        return $this->detail(ContentKind::PAGE, 'proceso');
    }

    public function legal(string $slug): Response
    {
        return $this->detail(ContentKind::PAGE, 'legal-'.$slug);
    }

    public function page(string $slug): Response
    {
        return $this->detail(ContentKind::PAGE, $slug);
    }

    private function detail(ContentKind $kind, string $slug): Response
    {
        $entry = ContentEntry::query()->published()->where('kind', $kind)->where('published_slug', $slug)->firstOrFail();
        $snapshot = $entry->publicData();

        return Inertia::render('public/detail', ['entry' => $snapshot, 'relatedServices' => ContentEntry::query()->published()->where('kind', ContentKind::SERVICE)->whereIn('id', $entry->published_snapshot['service_ids'] ?? [])->get()->map(fn (ContentEntry $service): array => $service->publicData()), 'seo' => ['title' => $snapshot['seo_title'] ?: $snapshot['title'], 'description' => $snapshot['seo_description'] ?: $snapshot['excerpt'], 'image' => $snapshot['media'][0]['url'] ?? null]]);
    }

    public function contact(): Response
    {
        $entry = ContentEntry::query()->published()->where('kind', ContentKind::PAGE)->where('published_slug', 'contacto')->first();

        return Inertia::render('public/contact', ['content' => $entry?->publicData()]);
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'min:2', 'max:120'], 'email' => ['required', 'email', 'max:180'], 'subject' => ['required', 'string', 'max:180'], 'message' => ['required', 'string', 'min:10', 'max:5000'], 'privacy_acknowledged' => ['accepted'], 'website' => ['nullable', 'string', 'max:200']], ['required' => 'Completa este campo.', 'email' => 'Escribe un correo válido.', 'min.string' => 'Escribe al menos :min caracteres.', 'max.string' => 'Usa como máximo :max caracteres.', 'accepted' => 'Confirma que comprendiste cómo se guardará tu consulta.']);
        if (empty($data['website'])) {
            ContactMessage::create(['reference' => (string) Str::uuid(), ...Arr::only($data, ['name', 'email', 'subject', 'message'])]);
        }
        Inertia::flash('contactSent', true);

        return to_route('public.contact');
    }

    public function start(Request $request): Response
    {
        $slug = $request->query('servicio', $request->session()->get('preferred_service_slug'));
        abort_if($slug !== null && ! is_string($slug), 404);
        $service = $slug ? ContentEntry::query()->published()->where('kind', ContentKind::SERVICE)->where('published_slug', $slug)->first() : null;
        if ($request->filled('servicio')) {
            abort_unless($service !== null, 404);
        }
        if (! $service) {
            $request->session()->forget('preferred_service_slug');
        }
        if ($service) {
            $request->session()->put('preferred_service_slug', $service->published_slug);
        }

        return Inertia::render('public/start', ['service' => $service?->publicData()]);
    }

    public function media(ContentEntry $entry, string $token): StreamedResponse
    {
        abort_unless($entry->published_at && $entry->published_at->lte(now()), 404);
        $images = $entry->published_snapshot['media'] ?? [];
        abort_unless(is_array($images), 404);
        foreach ($images as $image) {
            if (is_array($image) && ($image['token'] ?? null) === $token && is_string($image['path'] ?? null) && Storage::disk('content_public')->exists($image['path'])) {
                return Storage::disk('content_public')->response($image['path'], null, ['Cache-Control' => 'no-store, max-age=0', 'X-Content-Type-Options' => 'nosniff']);
            }
        }
        abort(404);
    }

    public function sitemap(): \Illuminate\Http\Response
    {
        $origin = rtrim((string) config('app.url'), '/');
        $paths = ['/', '/servicios', '/portfolio', '/contacto'];
        foreach (ContentEntry::query()->published()->get() as $entry) {
            $paths[] = $entry->publicData()['url'];
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach (array_unique($paths) as $path) {
            $xml .= '<url><loc>'.htmlspecialchars($origin.$path, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</loc></url>';
        }

        return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function robots(): \Illuminate\Http\Response
    {
        return response("User-agent: *\nAllow: /\nDisallow: /owner\nDisallow: /client\nDisallow: /staff\nDisallow: /dashboard\nDisallow: /settings\nDisallow: /design\nDisallow: /login\nDisallow: /register\nDisallow: /reset-password\nSitemap: ".rtrim((string) config('app.url'), '/')."/sitemap.xml\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
