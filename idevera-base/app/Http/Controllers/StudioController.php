<?php

namespace App\Http\Controllers;

use App\Enums\ContentKind;
use App\Models\ContentEntry;
use Inertia\Inertia;
use Inertia\Response;

class StudioController extends Controller
{
    public function home(): Response
    {
        return Inertia::render('welcome', [
            'designPreview' => app()->environment(['local', 'testing']),
            'services' => ContentEntry::query()->published()->where('kind', ContentKind::SERVICE)->orderBy('published_position')->orderBy('id')->limit(20)->get()->map(fn (ContentEntry $entry): array => $entry->publicData()),
            'portfolio' => ContentEntry::query()->published()->where('kind', ContentKind::PORTFOLIO)->where('published_featured', true)->orderBy('published_position')->orderBy('id')->limit(4)->get()->map(fn (ContentEntry $entry): array => $entry->publicData()),
        ]);
    }

    public function projectPreview(): Response
    {
        abort_unless(app()->environment(['local', 'testing']), 404);

        return Inertia::render('design/project');
    }
}
