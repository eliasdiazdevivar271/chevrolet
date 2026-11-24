<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:4096'],
            'starts_on' => ['nullable', 'date_format:Y-m-d'],
            'ends_on' => ['nullable', 'date_format:Y-m-d', Rule::when($this->filled('starts_on'), ['after_or_equal:starts_on'])],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute supera el tamaño permitido.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'Use una imagen JPG, JPEG, PNG o WebP.',
            'image.extensions' => 'Use una extensión JPG, JPEG, PNG o WebP.',
            'image.max' => 'La imagen no puede superar 4 MB.',
            'date_format' => 'Indique una fecha válida.',
            'ends_on.after_or_equal' => 'La fecha final no puede ser anterior a la inicial.',
            'boolean' => 'El campo :attribute no es válido.',
        ];
    }
}
