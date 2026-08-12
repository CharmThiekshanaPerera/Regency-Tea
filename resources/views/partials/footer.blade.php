@php
    $groups = \App\Models\ProductGroup::orderBy('sort')->take(6)->get();
    $brands = \App\Models\Brand::orderBy('sort')->get();
@endphp

<footer class="relative mt-24 overflow-hidden bg-stone-950 text-stone-300">
    <img src="{{ asset('images/sections/tea-pluckers.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-10">
    <div class="absolute inset-0 bg-stone-950/92"></div>

    <div class="relative mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:grid-cols-2 lg:grid-cols-4">

        <div>
            <p class="text-lg font-semibold text-white">{{ config('regency.company.name') }}</p>
            <p class="mt-2 text-sm text-stone-400">{{ config('regency.company.tagline') }}</p>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">Ranges</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($groups as $g)
                    <li><a href="{{ route('ranges') }}#{{ $g->slug }}" class="transition hover:text-emerald-400">{{ $g->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">Brands</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($brands as $b)
                    <li><a href="{{ route('brand.show', $b) }}" class="transition hover:text-emerald-400">{{ $b->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">Company</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('about') }}" class="transition hover:text-emerald-400">About</a></li>
                <li><a href="{{ route('ceylon-tea') }}" class="transition hover:text-emerald-400">Ceylon Tea</a></li>
                <li><a href="{{ route('health-benefits') }}" class="transition hover:text-emerald-400">Health Benefits</a></li>
                <li><a href="{{ route('private-label') }}" class="transition hover:text-emerald-400">Private Label</a></li>
                <li><a href="{{ route('media') }}" class="transition hover:text-emerald-400">Media Centre</a></li>
                <li><a href="{{ route('faqs') }}" class="transition hover:text-emerald-400">FAQs</a></li>
                <li><a href="{{ route('contact') }}" class="transition hover:text-emerald-400">Contact</a></li>
            </ul>
        </div>
    </div>

    <div class="relative border-t border-white/10 py-6 text-center text-xs text-stone-500">
        &copy; {{ date('Y') }} {{ config('regency.company.name') }}. All rights reserved.
    </div>
</footer>
