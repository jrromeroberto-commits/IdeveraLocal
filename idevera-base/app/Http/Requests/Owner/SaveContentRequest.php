<?php

namespace App\Http\Requests\Owner;

use App\Enums\ContentKind;
use App\Models\ContentEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class SaveContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $entry = $this->route('entry');

        return $entry instanceof ContentEntry ? ($this->user()?->can('update', $entry) ?? false) : ($this->user()?->can('create', ContentEntry::class) ?? false);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $entry = $this->route('entry');
        $kind = $entry instanceof ContentEntry ? $entry->kind->value : $this->input('kind');

        return [
            'kind' => ['required', $entry instanceof ContentEntry ? Rule::in([$kind]) : Rule::enum(ContentKind::class)],
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:120', 'regex:/\A[a-z0-9]+(?:-[a-z0-9]+)*\z/', Rule::unique('content_entries')->where('kind', $kind)->ignore($entry instanceof ContentEntry ? $entry->id : null)],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:30000'],
            'category' => ['nullable', 'string', 'max:100'],
            'art_style' => ['required', Rule::in(['orbit', 'forma'])],
            'seo_title' => ['nullable', 'string', 'max:100'],
            'seo_description' => ['nullable', 'string', 'max:180'],
            'position' => ['required', 'integer', 'min:0', 'max:10000'],
            'featured' => ['required', 'boolean'], 'is_example' => ['required', 'boolean'],
            'service_ids' => ['nullable', 'array', 'max:20'],
            'service_ids.*' => ['integer', 'distinct', Rule::exists('content_entries', 'id')->where('kind', 'SERVICE')],
            'lock_version' => [$entry instanceof ContentEntry ? 'required' : 'nullable', 'integer', 'min:1'],
            'images' => ['nullable', 'array', 'max:8'],
            'images.*' => [File::image()->types(['jpg', 'jpeg', 'png', 'webp'])->max(6144), 'dimensions:max_width=6000,max_height=6000'],
            'image_alt' => ['required_with:images', 'nullable', 'string', 'min:3', 'max:180'],
            'remove_media_ids' => ['nullable', 'array', 'max:8'],
            'remove_media_ids.*' => ['integer', 'distinct', Rule::exists('content_media', 'id')->where('content_entry_id', $entry instanceof ContentEntry ? $entry->id : 0)],
            'media_alt' => ['nullable', 'array'], 'media_alt.*' => ['required', 'string', 'min:3', 'max:180'],
            'published_at' => ['prohibited'], 'published_snapshot' => ['prohibited'], 'published_slug' => ['prohibited'], 'published_version' => ['prohibited'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return ['slug.regex' => 'Usa letras minúsculas, números y guiones.', '*.required' => 'Completa este campo.', 'images.*.image' => 'Selecciona una imagen JPG, PNG o WebP.', 'images.*.max' => 'Cada imagen puede pesar hasta 6 MB.', 'slug.unique' => 'Este identificador ya está en uso.'];
    }
}
