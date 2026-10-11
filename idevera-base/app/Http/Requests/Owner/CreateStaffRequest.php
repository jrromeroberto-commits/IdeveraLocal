<?php

namespace App\Http\Requests\Owner;

use App\Concerns\ProfileValidationRules;
use App\Enums\StaffSpecialty;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStaffRequest extends FormRequest
{
    use ProfileValidationRules;

    public function authorize(): bool
    {
        return $this->user()->can('createStaff', User::class);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            ...$this->profileRules(),
            'specialties' => ['present', 'array', 'max:7'],
            'specialties.*' => ['required', Rule::enum(StaffSpecialty::class), 'distinct'],
            'role' => ['prohibited'],
            'status' => ['prohibited'],
            'password' => ['prohibited'],
            'email_verified_at' => ['prohibited'],
        ];
    }
}
