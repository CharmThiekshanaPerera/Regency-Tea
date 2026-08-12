@php
    $groups = \App\Models\ProductGroup::orderBy('sort')->take(6)->get();
    $brands = \App\Models\Brand::orderBy('sort')->get();
@endphp

<footer class="mt-24 border-t border-stone-200 bg-stone-50">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:grid-cols-2 lg:grid-cols-4">

        <div>
            <p class="text-lg font-semibold">{{ config('regency.company.name') }}</p>
            <p class="mt-2 text-sm text-stone-600">{{ config('regency.company.tagline') }}</p>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">Ranges</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($groups as $g)
                    <li><a href="{{ route('ranges') }}#{{ $g->slug }}" class="hover:text-emerald-700">{{ $g->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">Brands</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($brands as $b)
                    <li><a href="{{ route('brand.show', $b) }}" class="hover:text-emerald-700">{{ $b->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">Company</p>
            <ul class="mt-3 space-y-2 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-emerald-700">About</a></li>
                <li><a href="{{ route('ceylon-tea') }}" class="hover:text-emerald-700">Ceylon Tea</a></li>
                <li><a href="{{ route('health-benefits') }}" class="hover:text-emerald-700">Health Benefits</a></li>
                <li><a href="{{ route('private-label') }}" class="hover:text-emerald-700">Private Label</a></li>
                <li><a href="{{ route('media') }}" class="hover:text-emerald-700">Media Centre</a></li>
                <li><a href="{{ route('faqs') }}" class="hover:text-emerald-700">FAQs</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-emerald-700">Contact</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-stone-200 py-6 text-center text-xs text-stone-500">
        &copy; {{ date('Y') }} {{ config('regency.company.name') }}. All rights reserved.
    </div>
</footer>
