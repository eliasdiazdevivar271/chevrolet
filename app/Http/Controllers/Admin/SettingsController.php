<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings', ['settings' => SiteSetting::first()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'dealer_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'digits_between:8,15'],
        ], [
            'whatsapp.digits_between' => 'Ingrese entre 8 y 15 dígitos, incluido el código de país.',
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'email' => 'Indique un correo electrónico válido.',
            'max' => 'El campo :attribute es demasiado largo.',
        ]);

        $settings = SiteSetting::first() ?? new SiteSetting;
        $settings->fill($data)->save();

        return redirect()->route('admin.settings.edit')->with('status', 'Datos de contacto actualizados.');
    }
}
