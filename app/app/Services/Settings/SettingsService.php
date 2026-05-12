<?php

namespace App\Services\Settings;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsService
{
    public function update(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $user->fill(Arr::only($data, ['name', 'email']));

            if (filled($data['password'] ?? null)) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            $user->preference()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'dark_mode' => (bool) ($data['dark_mode'] ?? true),
                    'preferred_model' => $data['preferred_model'] ?? 'gpt-4o-mini',
                    'preferred_temperature' => $data['preferred_temperature'] ?? 0.7,
                ]
            );
        });
    }

    public function defaultsFor(User $user): UserPreference
    {
        return $user->preference ?: $user->preference()->make([
            'dark_mode' => true,
            'preferred_model' => 'gpt-4o-mini',
            'preferred_temperature' => 0.7,
        ]);
    }
}
