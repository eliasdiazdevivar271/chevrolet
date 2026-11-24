<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'price' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:4096'],
            'featured' => ['sometimes', 'boolean'],
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
            'boolean' => 'El campo :attribute no es válido.',
        ];
    }
}
