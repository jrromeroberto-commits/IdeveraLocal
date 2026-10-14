<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContentEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', ContentEntry::class);

        return Inertia::render('owner/content/messages', ['messages' => ContactMessage::query()->latest('id')->paginate(15), 'unreadCount' => ContactMessage::query()->whereNull('read_at')->count()]);
    }

    public function read(ContactMessage $message): RedirectResponse
    {
        Gate::authorize('viewAny', ContentEntry::class);
        ContactMessage::query()->whereKey($message->id)->whereNull('read_at')->update(['read_at' => now()]);

        return to_route('owner.contact.index');
    }
}
