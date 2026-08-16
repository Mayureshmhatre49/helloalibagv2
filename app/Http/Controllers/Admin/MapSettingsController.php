<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MapApiService;
use Illuminate\Http\Request;

class MapSettingsController extends Controller
{
    public function __construct(protected MapApiService $mapApi) {}

    public function edit()
    {
        $settings = $this->mapApi->settings();
        $usage = $this->mapApi->usageSummary();

        return view('admin.map-settings.edit', compact('settings', 'usage'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'api_key' => 'nullable|string|max:255',
            'map_id' => 'nullable|string|max:255',
            'monthly_free_limit_map_loads' => 'required|integer|min:1',
            'monthly_free_limit_search' => 'required|integer|min:1',
            'auto_disable_threshold_percent' => 'required|integer|min:50|max:100',
        ]);

        $settings = $this->mapApi->settings();

        if (! empty($validated['api_key'])) {
            $settings->api_key = $validated['api_key'];
        }

        $settings->map_id = $validated['map_id'] ?? null;
        $settings->monthly_free_limit_map_loads = $validated['monthly_free_limit_map_loads'];
        $settings->monthly_free_limit_search = $validated['monthly_free_limit_search'];
        $settings->auto_disable_threshold_percent = $validated['auto_disable_threshold_percent'];
        $settings->save();

        return redirect()->route('admin.map-settings.edit')->with('success', 'Map settings saved.');
    }

    public function toggle()
    {
        $settings = $this->mapApi->settings();

        if (! $settings->enabled && empty($settings->api_key)) {
            return redirect()->route('admin.map-settings.edit')->with('error', 'Add an API key before enabling.');
        }

        $settings->enabled = ! $settings->enabled;

        if ($settings->enabled) {
            $settings->auto_disabled_at = null;
            $settings->auto_disabled_reason = null;
        }

        $settings->save();

        return redirect()->route('admin.map-settings.edit')
            ->with('success', $settings->enabled ? 'Google Maps enabled.' : 'Google Maps disabled.');
    }
}
