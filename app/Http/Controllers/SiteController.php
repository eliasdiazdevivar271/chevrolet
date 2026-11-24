<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Promotion;
use App\Models\SiteSetting;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteController extends Controller
{
    public function home(): View
    {
        return view('site.home', [
            'vehicles' => Vehicle::where('active', true)->where('featured', true)->orderBy('id')->get(),
            'promotions' => Promotion::visible()->orderByDesc('id')->get(),
        ]);
    }

    public function about(): View
    {
        return view('site.about');
    }

    public function vehicles(): View
    {
        return view('site.vehicles', ['vehicles' => Vehicle::where('active', true)->orderBy('id')->get()]);
    }

    public function promotions(): View
    {
        return view('site.promotions', ['promotions' => Promotion::visible()->orderByDesc('id')->get()]);
    }

    public function contact(Request $request): View
    {
        $vehicleName = $request->query('vehiculo');

        return view('site.contact', [
            'settings' => SiteSetting::first(),
            'subject' => is_string($vehicleName) && $vehicleName !== '' ? 'Consulta por '.mb_substr($vehicleName, 0, 137) : '',
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'Indique un correo electrónico válido.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute es demasiado largo.',
        ]);

        ContactMessage::create($data);

        return redirect()->route('contact.show')->with('success', 'Gracias. Su mensaje ha sido recibido.');
    }
}
