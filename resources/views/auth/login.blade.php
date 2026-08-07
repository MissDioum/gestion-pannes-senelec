<x-guest-layout>
    <!-- Session Status -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Bienvenue</h1>
        <p class="mt-2 text-sm text-slate-500">Connectez-vous pour accéder à votre espace de gestion des pannes.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-3">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">Se souvenir de moi</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-sky-600 hover:text-sky-800" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <div class="mt-2">
            <x-primary-button class="w-full justify-center">
                Connexion
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-slate-500">
        <p>Bienvenue sur l’interface de gestion des pannes.</p>
    </div>
</x-guest-layout>
