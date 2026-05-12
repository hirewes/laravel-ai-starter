<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UpdateSettingsRequest;
use App\Services\Settings\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(Request $request, SettingsService $settingsService)
    {
        return view('settings.edit', [
            'preference' => $settingsService->defaultsFor($request->user()),
        ]);
    }

    public function update(
        UpdateSettingsRequest $request,
        SettingsService $settingsService,
    ) {
        $settingsService->update($request->user(), $request->validated());

        return redirect()
            ->route('settings.edit')
            ->with('toast', 'Settings updated successfully.');
    }
}
