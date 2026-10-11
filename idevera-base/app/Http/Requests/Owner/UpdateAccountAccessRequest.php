<?php

namespace App\Http\Requests\Owner;

use App\Enums\ClientStatus;
use App\Enums\Role;
use App\Enums\StaffSpecialty;
use App\Enums\StaffStatus;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountAccessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manageAccess', $this->route('user'));
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var User $target */
        $target = $this->route('user');

        return [
            'status' => ['required', Rule::enum($target->role === Role::CLIENT ? ClientStatus::class : StaffStatus::class)],
            'specialties' => $target->role === Role::STAFF ? ['present', 'array', 'max:7'] : ['prohibited'],
            'specialties.*' => ['required', Rule::enum(StaffSpecialty::class), 'distinct'],
            'role' => ['prohibited'],
            'email_verified_at' => ['prohibited'],
        ];
    }
}
