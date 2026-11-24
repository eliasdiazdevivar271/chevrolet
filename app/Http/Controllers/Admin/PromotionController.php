<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        return view('admin.promotions.index', ['promotions' => Promotion::orderByDesc('id')->paginate(20)]);
    }

    public function create(): View
    {
        return view('admin.promotions.form', ['promotion' => new Promotion]);
    }

    public function store(PromotionRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['starts_on'] = $request->validated('starts_on');
        $data['ends_on'] = $request->validated('ends_on');
        $data['active'] = $request->boolean('active');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promotions', 'public');
            if ($path === false) {
                throw ValidationException::withMessages(['image' => 'No se pudo guardar la imagen.']);
            }
            $data['image_path'] = 'storage/'.$path;
        }
        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('status', 'Promoción creada.');
    }

    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.form', ['promotion' => $promotion]);
    }

    public function update(PromotionRequest $request, Promotion $promotion): RedirectResponse
    {
        $data = $request->safe()->except(['image']);
        $data['starts_on'] = $request->validated('starts_on');
        $data['ends_on'] = $request->validated('ends_on');
        $data['active'] = $request->boolean('active');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promotions', 'public');
            if ($path === false) {
                throw ValidationException::withMessages(['image' => 'No se pudo guardar la imagen.']);
            }
            $data['image_path'] = 'storage/'.$path;
        }
        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('status', 'Promoción actualizada.');
    }

    public function destroy(Promotion $promotion): RedirectResponse
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('status', 'Promoción eliminada.');
    }
}
