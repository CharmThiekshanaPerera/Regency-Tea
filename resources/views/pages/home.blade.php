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

<x-home-hero />

<div id="below-hero"></div>

{{-- Stats strip --}}
<section class="border-b border-stone-200 bg-stone-950 py-12 text-white" data-reveal>
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 sm:grid-cols-4">
        @foreach ([
            ['to' => $stats['years'], 'suffix' => '+', 'label' => 'Years of craft'],
            ['to' => $stats['countries'], 'suffix' => '+', 'label' => 'Export markets'],
            ['to' => $stats['brands'], 'suffix' => '', 'label' => 'Global brands'],
            ['to' => $stats['products'], 'suffix' => '+', 'label' => 'Teas &amp; blends'],
        ] as $i => $stat)
            <div class="text-center sm:text-left" style="--reveal-delay: {{ $i * 100 }}ms">
                <p class="text-4xl font-semibold tracking-tight text-emerald-400 sm:text-5xl">
                    <span data-count-to="{{ $stat['to'] }}" data-count-suffix="{{ $stat['suffix'] }}">0{{ $stat['suffix'] }}</span>
                </p>
                <p class="mt-1 text-xs uppercase tracking-widest text-stone-400 sm:text-sm">{!! $stat['label'] !!}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Brands --}}
@if ($brands->isNotEmpty())
<section class="py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Our portfolio</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Four brands, trusted worldwide</p>
        </div>

        <div class="relative mt-10 overflow-hidden rounded-2xl shadow-xl">
            <img src="{{ asset('images/sections/brands-collage.jpg') }}" alt="Hyleys, Lakma, Dr. Tea and Truly Ceylon product ranges"
                 class="h-40 w-full object-cover sm:h-56 lg:h-72" loading="lazy">
            <div class="absolute inset-0 bg-gradient-to-t from-stone-950/70 via-stone-950/0 to-stone-950/0"></div>
        </div>

        <ul role="list" class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
            @foreach ($brands as $i => $brand)
                <li style="--reveal-delay: {{ $i * 80 }}ms">
                    <a href="{{ route('brand.show', $brand) }}"
                       class="group relative block aspect-square overflow-hidden rounded-2xl shadow-md transition duration-500 hover:-translate-y-1 hover:shadow-2xl">
                        @if ($brand->logo_path)
                            <x-media-image :path="$brand->logo_path" :alt="$brand->name"
                                           class="h-full w-full !object-cover animate-kenburns transition duration-700 ease-out group-hover:scale-110" />
                        @else
                            <div class="flex h-full items-center justify-center bg-stone-100 text-2xl font-semibold">{{ $brand->name }}</div>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- Ranges --}}
@if ($groups->isNotEmpty())
<section class="bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">The collection</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Explore our ranges</p>
                <p class="mt-2 max-w-lg text-stone-600">From single-region Ceylon black tea to wellness infusions.</p>
            </div>
            <a href="{{ route('ranges') }}" class="group inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                All ranges <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>

        <ul role="list" class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($groups as $i => $group)
                <li style="--reveal-delay: {{ $i * 60 }}ms">
                    <a href="{{ route('ranges') }}#{{ $group->slug }}"
                       class="group relative flex h-60 flex-col justify-end overflow-hidden rounded-xl shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl">
                        @if ($group->cover_image)
                            <x-media-image :path="$group->cover_image" alt=""
                                           class="absolute inset-0 h-full w-full !object-cover transition duration-500 group-hover:scale-105" />
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 to-emerald-950"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/85 via-stone-950/25 to-transparent"></div>

                        <div class="relative p-6 text-white">
                            <span class="text-xs font-semibold tracking-widest text-emerald-300">
                                {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <h3 class="mt-1 text-lg font-medium">{{ $group->name }}</h3>
                            <p class="mt-1 text-sm text-stone-300">
                                {{ $group->categories->count() }} {{ Str::plural('category', $group->categories->count()) }}
                            </p>
                            <span class="mt-3 inline-flex items-center gap-1 text-sm font-semibold opacity-0 transition group-hover:opacity-100">
                                Browse range <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                            </span>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif

{{-- Our craft --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div class="relative">
            <img src="{{ asset('images/sections/ingredients.jpg') }}" alt="Ceylon tea leaves and natural flavour ingredients"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl" loading="lazy">
            <img src="{{ asset('images/hero/leaf-accent-2.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none absolute -bottom-8 -left-8 hidden w-28 sm:block">
        </div>
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Our craft</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Graded by taste, not guesswork</p>
            <p class="mt-5 text-stone-600">
                Our team of professional tea tasters grades over <strong>8,000 lots of tea every week</strong>
                and tastes more than <strong>500 cups a day</strong>, selecting the leaf that suits each market
                we serve — from classic black &amp; green tea to specialty blends with real fruit and herbs.
            </p>
            <p class="mt-4 text-stone-600">
                Every batch is packed at our own <strong>90,000 sq ft</strong> facility under strict food-safety
                systems, giving us a combined capacity of <strong>2.75 million tea bags a day</strong>.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('ceylon-tea') }}"
                   class="rounded-full bg-emerald-800 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-emerald-900">
                    The story of Ceylon tea
                </a>
                <a href="{{ route('about') }}"
                   class="rounded-full border border-stone-300 px-6 py-3 text-sm font-semibold transition hover:-translate-y-0.5 hover:border-emerald-600 hover:text-emerald-700">
                    About Regency Teas
                </a>
            </div>
        </div>
    </div>
</section>

{{-- New arrivals --}}
@if ($newArrivals->isNotEmpty())
<section class="bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Fresh in</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">New arrivals</p>
            </div>
            <a href="{{ route('products', ['sort' => 'newest']) }}" class="group inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                View all <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>
        <div class="mt-10"><x-product-grid :products="$newArrivals" /></div>
    </div>
</section>
@endif

{{-- Certifications --}}
<section class="border-y border-stone-200 bg-white py-14" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <p class="text-center text-sm font-semibold uppercase tracking-widest text-stone-500">
            Certified quality, every batch
        </p>
        <div class="mt-8 grid grid-cols-2 items-center gap-8 sm:grid-cols-4">
            @foreach ([
                ['file' => 'iso-22000.png', 'label' => 'ISO 22000'],
                ['file' => 'fssc-22000.png', 'label' => 'FSSC 22000'],
                ['file' => 'usda-organic.png', 'label' => 'USDA Organic'],
                ['file' => 'halal.png', 'label' => 'Halal certified'],
            ] as $badge)
                <div class="flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('images/badges/'.$badge['file']) }}" alt="{{ $badge['label'] }}"
                         class="h-16 w-auto object-contain grayscale transition duration-300 hover:grayscale-0" loading="lazy">
                    <span class="text-xs font-medium text-stone-500">{{ $badge['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Private label CTA --}}
<section class="relative overflow-hidden bg-stone-950 py-20 text-white" data-reveal>
    <img src="{{ asset('images/hero/plantation.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-25">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/90 to-stone-950/60"></div>

    <div class="relative mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-8 px-4">
        <div class="max-w-xl">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Private label &amp; bulk supply</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Your brand, our craftsmanship</p>
            <p class="mt-4 text-stone-300">
                We produce private-label tea for retailers and foodservice worldwide, from blending
                and packing to export documentation.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('private-label') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                Learn more
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                Talk to us
            </a>
        </div>
    </div>
</section>

{{-- Latest news --}}
@if ($latestPosts->isNotEmpty())
<section class="py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Stay in the loop</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">From the Media Centre</p>
            </div>
            <a href="{{ route('media') }}" class="group inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                All news <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
            </a>
        </div>
        <ul role="list" class="mt-10 grid gap-8 sm:grid-cols-3">
            @foreach ($latestPosts as $i => $post)
                <li style="--reveal-delay: {{ $i * 100 }}ms">
                    <a href="{{ route('media.show', $post) }}" class="group block">
                        <div class="overflow-hidden rounded-lg">
                            <x-media-image :path="$post->featured_image_path" :alt="$post->title"
                                           class="aspect-[16/10] w-full !object-cover transition duration-500 group-hover:scale-105" />
                        </div>
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
