<header class="sticky top-0 z-40 border-b border-white/10 bg-slate-950/75 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-400 text-base font-bold text-slate-950 shadow-lg shadow-blue-500/20">AI</span>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Laravel SaaS Starter</p>
                    <p class="text-sm font-semibold text-white">Laravel AI Starter</p>
                </div>
            </a>

            <nav class="hidden items-center gap-2 md:flex">
                @php($navLinks = [
                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                    ['label' => 'AI Chat', 'route' => 'chat.index'],
                    ['label' => 'Settings', 'route' => 'settings.edit'],
                ])
                @foreach ($navLinks as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        class="rounded-full px-4 py-2 text-sm transition {{ request()->routeIs($link['route']) || request()->routeIs($link['route'].'.*') ? 'bg-white text-slate-950 shadow-lg' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="flex items-center gap-3">
            <button
                type="button"
                class="hidden rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200 transition hover:bg-white/10 sm:inline-flex"
                @click="toggleDarkMode()"
            >
                Dark mode
            </button>

            <div class="hidden rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-300 lg:block">
                {{ auth()->user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-slate-200 transition hover:bg-white/10">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
