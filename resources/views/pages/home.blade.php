@extends('layouts.app')

@section('title', config('regency.company.name').' — '.config('regency.company.tagline'))
@section('meta_description', 'Regency Teas is a pure Ceylon tea exporter in Sri Lanka, producing Hyleys, Lakma and Truly Ceylon teas for wholesale, HORECA and private label markets worldwide.')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => config('regency.company.name'),
    'description' => config('regency.company.tagline'),
    'url' => url('/'),
    'logo' => asset('images/logo.svg'),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

<x-hero-slider :slides="$slides ?? collect()" />

{{-- Brands --}}
@if ($brands->isNotEmpty())
<section class="border-b border-stone-200 py-14">
    <div class="mx-auto max-w-7xl px-4">
        <h2 class="text-center text-sm font-semibold uppercase tracking-widest text-stone-500">Our brands</h2>
        <ul role="list" class="mt-8 grid grid-cols-2 gap-6 sm:grid-cols-4">
            @foreach ($brands as $brand)
                <li>
                    <a href="{{ route('brand.show', $brand) }}"
                       class="flex h-28 flex-col items-center justify-center gap-2 rounded-xl border border-stone-200 p-4 transition hover:border-emerald-600 hover:shadow">
                        @if ($brand->logo_path)
                            <x-media-image :path="$brand->logo_path" :alt="$brand->name" class="h-10 w-auto" />
                        @else
                            <span class="text-lg font-semibold">{{ $brand->name }}</span>
                        @endif
                        <span class="text-xs text-stone-500">{{ $brand->products_count ?? '' }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- Ranges --}}
@if ($groups->isNotEmpty())
<section class="py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-semibold tracking-tight">Explore our ranges</h2>
                <p class="mt-2 text-stone-600">From single-region Ceylon black tea to wellness infusions.</p>
            </div>
            <a href="{{ route('ranges') }}" class="text-sm font-semibold text-emerald-800 hover:underline">
                All ranges &rarr;
            </a>
        </div>

        <ul role="list" class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($groups as $group)
                <li>
                    <a href="{{ route('ranges') }}#{{ $group->slug }}"
                       class="flex h-full flex-col rounded-xl border border-stone-200 p-6 transition hover:border-emerald-600 hover:shadow">
                        <h3 class="text-lg font-medium">{{ $group->name }}</h3>
                        <p class="mt-1 text-sm text-stone-500">
                            {{ $group->categories->count() }} {{ Str::plural('category', $group->categories->count()) }}
                        </p>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- New arrivals --}}
@if ($newArrivals->isNotEmpty())
<section class="bg-stone-50 py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h2 class="text-3xl font-semibold tracking-tight">New arrivals</h2>
            <a href="{{ route('products', ['sort' => 'newest']) }}" class="text-sm font-semibold text-emerald-800 hover:underline">
                View all &rarr;
            </a>
        </div>
        <div class="mt-8"><x-product-grid :products="$newArrivals" /></div>
    </div>
</section>
@endif

{{-- Private label CTA --}}
<section class="bg-emerald-900 py-16 text-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-8 px-4">
        <div class="max-w-xl">
            <h2 class="text-3xl font-semibold tracking-tight">Private label &amp; bulk supply</h2>
            <p class="mt-3 text-emerald-100">
                We produce private-label tea for retailers and foodservice worldwide, from blending
                and packing to export documentation.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('private-label') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 hover:bg-emerald-50">
                Learn more
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold hover:bg-white/10">
                Talk to us
            </a>
        </div>
    </div>
</section>

{{-- Latest news --}}
@if ($latestPosts->isNotEmpty())
<section class="py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h2 class="text-3xl font-semibold tracking-tight">From the Media Centre</h2>
            <a href="{{ route('media') }}" class="text-sm font-semibold text-emerald-800 hover:underline">All news &rarr;</a>
        </div>
        <ul role="list" class="mt-8 grid gap-8 sm:grid-cols-3">
            @foreach ($latestPosts as $post)
                <li>
                    <a href="{{ route('media.show', $post) }}" class="group block">
                        <x-media-image :path="$post->featured_image_path" :alt="$post->title"
                                       class="aspect-[16/10] w-full rounded-lg !object-cover" />
                        <time class="mt-3 block text-xs uppercase tracking-wide text-stone-500">
                            {{ $post->published_at?->format('j F Y') }}
                        </time>
                        <h3 class="mt-1 font-medium group-hover:text-emerald-700">{{ $post->title }}</h3>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

@endsection
