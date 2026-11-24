<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SiteContentController extends Controller
{
    public function edit(): View
    {
        return view('admin.site-content', ['settings' => SiteSetting::first()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'home_title' => ['nullable', 'string', 'max:120'],
            'home_description' => ['nullable', 'string', 'max:500'],
            'about_title' => ['nullable', 'string', 'max:120'],
            'about_description' => ['nullable', 'string', 'max:1500'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'Use una imagen JPG, JPEG, PNG o WebP.',
            'image.extensions' => 'Use una extensión JPG, JPEG, PNG o WebP.',
            'image.max' => 'La imagen no puede superar 4 MB.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute es demasiado largo.',
        ]);

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('site', 'public');
            if ($path === false) {
                throw ValidationException::withMessages(['hero_image' => 'No se pudo guardar la imagen.']);
            }
            $data['hero_image_path'] = 'storage/'.$path;
        }
        unset($data['hero_image']);

        $settings = SiteSetting::first() ?? new SiteSetting(['dealer_name' => 'Chevrolet']);
        $settings->fill($data)->save();

        return redirect()->route('admin.content.edit')->with('status', 'Contenido actualizado.');
    }
}
