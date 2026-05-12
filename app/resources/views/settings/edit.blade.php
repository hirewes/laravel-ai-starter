@extends('layouts.app')

@section('title', 'Settings | Laravel AI Starter')

@section('content')
    <div class="space-y-8">
        <div class="max-w-3xl">
            <p class="text-sm uppercase tracking-[0.25em] text-slate-400">Settings</p>
            <h1 class="mt-3 text-4xl font-semibold text-white">Manage your account and AI defaults.</h1>
            <p class="mt-4 text-base leading-7 text-slate-300">
                Update your profile, tune your preferred OpenAI model, and keep the interface aligned with your dark-mode preference.
            </p>
        </div>

        <form method="POST" action="{{ route('settings.update') }}" class="grid gap-6 xl:grid-cols-[1fr_0.9fr]">
            @csrf
            @method('PATCH')

            <section class="card-glass space-y-6 p-8">
                <div>
                    <h2 class="text-xl font-semibold text-white">Account</h2>
                    <p class="mt-2 text-sm text-slate-400">Profile information used throughout the starter.</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-slate-200">Name</label>
                        <input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-input" required>
                        @error('name') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-medium text-slate-200">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" class="form-input" required>
                        @error('email') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-medium text-slate-200">New password</label>
                        <input id="password" name="password" type="password" class="form-input" placeholder="Leave blank to keep current">
                        @error('password') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-200">Confirm password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-input">
                    </div>
                </div>
            </section>

            <section class="card-glass space-y-6 p-8">
                <div>
                    <h2 class="text-xl font-semibold text-white">AI preferences</h2>
                    <p class="mt-2 text-sm text-slate-400">Stored per user while API credentials remain environment-based.</p>
                </div>

                <div>
                    <label for="preferred_model" class="mb-2 block text-sm font-medium text-slate-200">Preferred model</label>
                    <input id="preferred_model" name="preferred_model" value="{{ old('preferred_model', $preference->preferred_model) }}" class="form-input" required>
                    @error('preferred_model') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="preferred_temperature" class="mb-2 block text-sm font-medium text-slate-200">Temperature</label>
                    <input id="preferred_temperature" name="preferred_temperature" type="number" step="0.1" min="0" max="2" value="{{ old('preferred_temperature', $preference->preferred_temperature) }}" class="form-input" required>
                    @error('preferred_temperature') <p class="mt-2 text-sm text-rose-300">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-4 text-sm text-slate-300">
                    <input type="hidden" name="dark_mode" value="0">
                    <input type="checkbox" name="dark_mode" value="1" @checked(old('dark_mode', $preference->dark_mode)) class="rounded border-white/20 bg-slate-950 text-blue-500 focus:ring-blue-500">
                    Default to dark mode for the in-app workspace
                </label>
                @error('dark_mode') <p class="text-sm text-rose-300">{{ $message }}</p> @enderror

                <button type="submit" class="btn-primary w-full justify-center">Save settings</button>
            </section>
        </form>
    </div>
@endsection
