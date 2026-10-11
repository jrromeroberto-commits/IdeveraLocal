<?php

namespace App\Http\Controllers\Owner;

use App\Actions\Accounts\CreateStaff;
use App\Actions\Accounts\UpdateAccountAccess;
use App\Enums\ClientStatus;
use App\Enums\Role;
use App\Enums\StaffSpecialty;
use App\Enums\StaffStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\CreateStaffRequest;
use App\Http\Requests\Owner\UpdateAccountAccessRequest;
use App\Models\AuditEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class AccountController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', User::class);

        return Inertia::render('owner/accounts', [
            'accounts' => User::query()->whereIn('role', [Role::CLIENT, Role::STAFF])->orderBy('id')->paginate(20)
                ->through(fn (User $user) => [
                    'id' => $user->id, 'name' => $user->name, 'email' => $user->email,
                    'role' => $user->role->value, 'status' => $user->accountStatus(),
                    'verified' => $user->hasVerifiedEmail(),
                    'specialties' => $user->staffProfile()->first()->specialties ?? [],
                ]),
            'clientStatuses' => array_column(ClientStatus::cases(), 'value'),
            'staffStatuses' => array_column(StaffStatus::cases(), 'value'),
            'specialtyOptions' => array_column(StaffSpecialty::cases(), 'value'),
            'auditEvents' => AuditEvent::query()->latest('id')->limit(20)->get(['id', 'actor_id', 'target_user_id', 'event', 'metadata', 'created_at']),
        ]);
    }

    public function store(CreateStaffRequest $request, CreateStaff $create): RedirectResponse
    {
        $create->handle($request->user(), $request->string('name')->toString(), $request->string('email')->toString(), $request->validated('specialties'));
        Inertia::flash('toast', ['type' => 'success', 'message' => 'Cuenta STAFF creada. La persona puede establecer su contraseña desde Recuperar contraseña y verificar su correo.']);

        return to_route('owner.accounts.index');
    }

    public function update(UpdateAccountAccessRequest $request, User $user, UpdateAccountAccess $update): RedirectResponse
    {
        $update->handle($request->user(), $user, $request->string('status')->toString(), $request->validated('specialties', []));
        Inertia::flash('toast', ['type' => 'success', 'message' => 'Acceso actualizado.']);

        return to_route('owner.accounts.index');
    }
}
