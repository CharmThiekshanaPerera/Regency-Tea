@php
    $nav = \App\Models\MenuItem::where('menu', 'main')->where('is_active', true)->orderBy('sort')->get();
@endphp

<header class="sticky top-0 z-40 border-b border-stone-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4">

        <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ config('regency.company.name') }} home">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('regency.company.name') }}" class="h-9 w-auto sm:h-10">
        </a>

        <nav class="hidden lg:block" aria-label="Primary">
            <ul class="flex items-center gap-7 text-sm font-medium">
                @foreach ($nav as $item)
                    <li>
                        <a href="{{ $item->url }}"
                           @class([
                               'transition hover:text-emerald-700',
                               'text-emerald-700' => request()->is(ltrim($item->url, '/') ?: '/'),
                           ])>{{ $item->label }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-3">
            <form action="{{ route('search') }}" method="get" class="hidden md:block" role="search">
                <label for="site-search" class="sr-only">Search products</label>
                <input id="site-search" type="search" name="q" value="{{ request('q') }}"
                       placeholder="Search teas…"
                       class="w-44 rounded-full border border-stone-300 px-4 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
            </form>

            <a href="{{ route('contact') }}"
               class="rounded-full bg-emerald-800 px-5 py-2 text-sm font-semibold text-white transition hover:bg-emerald-900">
                Enquire
            </a>

            <button type="button" class="lg:hidden" aria-label="Open menu" aria-expanded="false" data-menu-toggle>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <nav class="hidden border-t border-stone-200 lg:hidden" data-mobile-menu aria-label="Mobile">
        <ul class="space-y-1 px-4 py-3">
            @foreach ($nav as $item)
                <li><a href="{{ $item->url }}" class="block py-2">{{ $item->label }}</a></li>
            @endforeach
        </ul>
    </nav>
</header>
