<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(): View
    {
        return view('admin.vehicles.index', ['vehicles' => Vehicle::orderByDesc('id')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.vehicles.form', ['vehicle' => new Vehicle]);
    }

    public function store(VehicleRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['featured'] = $request->boolean('featured');
        $data['active'] = $request->boolean('active');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('vehicles', 'public');
            if ($path === false) {
                throw ValidationException::withMessages(['image' => 'No se pudo guardar la imagen.']);
            }
            $data['image_path'] = 'storage/'.$path;
        }
        Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehículo creado.');
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('admin.vehicles.form', ['vehicle' => $vehicle]);
    }

    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['featured'] = $request->boolean('featured');
        $data['active'] = $request->boolean('active');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('vehicles', 'public');
            if ($path === false) {
                throw ValidationException::withMessages(['image' => 'No se pudo guardar la imagen.']);
            }
            $data['image_path'] = 'storage/'.$path;
        }
        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehículo actualizado.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('status', 'Vehículo eliminado.');
    }
}
