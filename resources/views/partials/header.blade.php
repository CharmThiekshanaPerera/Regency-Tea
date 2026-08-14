@php
    $nav = \App\Models\MenuItem::where('menu', 'main')->where('is_active', true)->orderBy('sort')->get();
@endphp

<header class="sticky top-0 z-40 border-b border-stone-200 bg-white/95 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3.5">

        <a href="{{ route('home') }}" class="shrink-0" aria-label="{{ config('regency.company.name') }} home">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('regency.company.name') }}" class="h-8 w-auto sm:h-9">
        </a>

        <nav class="hidden xl:block" aria-label="Primary">
            <ul class="flex items-center gap-5 whitespace-nowrap text-sm font-medium xl:gap-6">
                @foreach ($nav as $item)
                    @php $isActive = request()->is(ltrim($item->url, '/') ?: '/'); @endphp
                    <li>
                        <a href="{{ $item->url }}"
                           @class([
                               'group relative inline-block py-1.5 transition-colors',
                               'text-emerald-700' => $isActive,
                               'text-stone-700 hover:text-emerald-700' => ! $isActive,
                           ])>
                            {{ $item->label }}
                            <span @class([
                                      'absolute inset-x-0 -bottom-0.5 h-0.5 origin-left rounded-full bg-emerald-600 transition-transform duration-300 ease-out',
                                      'scale-x-100' => $isActive,
                                      'scale-x-0 group-hover:scale-x-100' => ! $isActive,
                                  ])></span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-2">
            <div x-data="{ open: false }" class="relative" @keydown.escape.window="open = false">
                <button type="button" @click="open = !open"
                        class="flex h-9 items-center gap-1 rounded-full px-2.5 text-sm font-medium text-stone-600 transition hover:bg-stone-100 hover:text-emerald-700"
                        :aria-expanded="open.toString()" aria-label="{{ __('site.nav.language') }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" d="M3 12h18M12 3c2.5 2.7 4 6 4 9s-1.5 6.3-4 9c-2.5-2.7-4-6-4-9s1.5-6.3 4-9Z"/>
                    </svg>
                    <span class="uppercase">{{ app()->getLocale() }}</span>
                </button>

                <div x-show="open" x-cloak x-transition.origin.top.right
                     @click.outside="open = false"
                     class="absolute right-0 top-full mt-2 w-40 overflow-hidden rounded-xl border border-stone-200 bg-white py-1.5 shadow-lg">
                    @foreach (config('regency.locales') as $code => $locale)
                        <a href="{{ route('locale.set', $code) }}"
                           @class([
                               'flex items-center justify-between px-4 py-2 text-sm transition hover:bg-stone-50',
                               'font-semibold text-emerald-700' => app()->getLocale() === $code,
                               'text-stone-700' => app()->getLocale() !== $code,
                           ])>
                            {{ $locale['native'] }}
                            @if (app()->getLocale() === $code)
                                <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false }" class="relative hidden md:block" @keydown.escape.window="open = false">
                <button type="button" @click="open = !open; if (open) $nextTick(() => $refs.searchInput.focus())"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-stone-500 transition hover:bg-stone-100 hover:text-emerald-700"
                        :aria-expanded="open.toString()" aria-label="{{ __('site.nav.search') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round" d="m20 20-3.5-3.5"/>
                    </svg>
                </button>

                <form x-show="open" x-cloak x-transition.origin.top.right
                      @click.outside="open = false"
                      action="{{ route('search') }}" method="get" role="search"
                      class="absolute right-0 top-full mt-2 w-64 rounded-xl border border-stone-200 bg-white p-2 shadow-lg">
                    <label for="site-search" class="sr-only">{{ __('site.nav.search') }}</label>
                    <input id="site-search" x-ref="searchInput" type="search" name="q" value="{{ request('q') }}"
                           placeholder="{{ __('site.nav.search_placeholder') }}"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm focus:border-emerald-600 focus:outline-none focus:ring-1 focus:ring-emerald-600">
                </form>
            </div>

            <a href="{{ route('contact') }}"
               class="rounded-full bg-emerald-800 px-5 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-emerald-900 hover:shadow-md">
                {{ __('site.nav.enquire') }}
            </a>

            <button type="button"
                    class="flex h-9 w-9 items-center justify-center rounded-full text-stone-600 transition hover:bg-stone-100 xl:hidden"
                    aria-label="{{ __('site.nav.open_menu') }}" aria-expanded="false" data-menu-toggle>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <nav class="hidden border-t border-stone-200 xl:hidden" data-mobile-menu aria-label="Mobile">
        <ul class="space-y-1 px-4 py-3">
            @foreach ($nav as $item)
                <li>
                    <a href="{{ $item->url }}"
                       @class([
                           'block rounded-lg px-3 py-2 font-medium transition',
                           'bg-emerald-50 text-emerald-700' => request()->is(ltrim($item->url, '/') ?: '/'),
                           'text-stone-700 hover:bg-stone-50' => ! request()->is(ltrim($item->url, '/') ?: '/'),
                       ])>{{ $item->label }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
</header>
