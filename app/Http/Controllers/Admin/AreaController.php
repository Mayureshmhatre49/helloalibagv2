<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('listings')->orderBy('name')->get();
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.areas.form', ['area' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:areas',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        Area::create($validated);

        return redirect()->route('admin.areas.index')->with('success', 'Area created.');
    }

    public function edit(Area $area)
    {
        return view('admin.areas.form', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:areas,slug,' . $area->id,
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $area->update($validated);

        return redirect()->route('admin.areas.index')->with('success', 'Area updated.');
    }

    public function destroy(Area $area)
    {
        if ($area->listings()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete an area with existing listings.');
        }

        $area->delete();
        return redirect()->route('admin.areas.index')->with('success', 'Area deleted.');
    }
}
