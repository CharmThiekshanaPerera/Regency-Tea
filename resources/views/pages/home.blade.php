@extends('layouts.app')

@section('title', config('regency.company.name').' — '.config('regency.company.tagline'))
@section('meta_description', __('pages/home.meta.description'))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => config('regency.company.name'),
    'description' => config('regency.company.tagline'),
    'url' => url('/'),
    'logo' => asset('images/logo.png'),
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
            ['to' => $stats['years'], 'suffix' => '+', 'label' => __('pages/home.stats.years')],
            ['to' => $stats['countries'], 'suffix' => '+', 'label' => __('pages/home.stats.countries')],
            ['to' => $stats['brands'], 'suffix' => '', 'label' => __('pages/home.stats.brands')],
            ['to' => $stats['products'], 'suffix' => '+', 'label' => __('pages/home.stats.products')],
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
@php
    // Hardcoded rather than $brand->logo_path — the WordPress importer never
    // populates that field (see ImportWordPress::importBrands()), so relying
    // on it silently degrades to a text-only fallback after every re-import.
    $brandMarks = [
        'hyleys'       => 'images/curved-hero/marks/hyleys.png',
        'lakma'        => 'images/curved-hero/marks/lakma.png',
        'truly-ceylon' => 'images/curved-hero/marks/truly-ceylon.png',
        'dr-tea'       => 'images/curved-hero/marks/dr-tea.png',
    ];
@endphp
@if ($brands->isNotEmpty())
<section class="py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/home.brands.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/home.brands.heading') }}</p>
        </div>

        <ul role="list" class="mt-12 grid grid-cols-2 gap-5 sm:gap-6 lg:grid-cols-4">
            @foreach ($brands as $i => $brand)
                @if (isset($brandMarks[$brand->slug]))
                    <li style="--reveal-delay: {{ $i * 80 }}ms">
                        <a href="{{ route('brand.show', $brand) }}"
                           class="group flex h-32 items-center justify-center rounded-2xl border border-stone-200 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-xl sm:h-40">
                            <img src="{{ asset($brandMarks[$brand->slug]) }}" alt="{{ $brand->name }}"
                                 class="max-h-full max-w-full object-contain transition duration-300 group-hover:scale-105">
                        </a>
                    </li>
                @endif
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
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/home.ranges.eyebrow') }}</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/home.ranges.heading') }}</p>
                <p class="mt-2 max-w-lg text-stone-600">{{ __('pages/home.ranges.description') }}</p>
            </div>
            <a href="{{ route('ranges') }}" class="group inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                {{ __('pages/home.ranges.all_link') }} <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
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
                                {{ __('pages/home.ranges.browse') }} <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
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
            <img src="{{ asset('images/sections/ingredients.jpg') }}" alt="{{ __('pages/home.craft.image_alt') }}"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl" loading="lazy">
            <img src="{{ asset('images/hero/leaf-accent-2.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none absolute -bottom-8 -left-8 hidden w-28 sm:block">
        </div>
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/home.craft.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/home.craft.heading') }}</p>
            <p class="mt-5 text-stone-600">
                {!! __('pages/home.craft.paragraph_1') !!}
            </p>
            <p class="mt-4 text-stone-600">
                {!! __('pages/home.craft.paragraph_2') !!}
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('ceylon-tea') }}"
                   class="rounded-full bg-emerald-800 px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-emerald-900">
                    {{ __('pages/home.craft.cta_ceylon') }}
                </a>
                <a href="{{ route('about') }}"
                   class="rounded-full border border-stone-300 px-6 py-3 text-sm font-semibold transition hover:-translate-y-0.5 hover:border-emerald-600 hover:text-emerald-700">
                    {{ __('pages/home.craft.cta_about') }}
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
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/home.new_arrivals.eyebrow') }}</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/home.new_arrivals.heading') }}</p>
            </div>
            <a href="{{ route('products', ['sort' => 'newest']) }}" class="group inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                {{ __('pages/home.new_arrivals.view_all') }} <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
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
            {{ __('pages/home.certifications.heading') }}
        </p>
        <div class="mt-8 grid grid-cols-2 items-center gap-8 sm:grid-cols-4">
            @foreach ([
                ['file' => 'iso-22000.png', 'label' => __('pages/home.certifications.iso')],
                ['file' => 'fssc-22000.png', 'label' => __('pages/home.certifications.fssc')],
                ['file' => 'usda-organic.png', 'label' => __('pages/home.certifications.usda')],
                ['file' => 'halal.png', 'label' => __('pages/home.certifications.halal')],
            ] as $badge)
                <div class="flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('images/badges/'.$badge['file']) }}" alt="{{ $badge['label'] }}"
                         class="h-24 w-auto object-contain transition duration-300 hover:scale-110 sm:h-28" loading="lazy">
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
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">{{ __('pages/home.private_label.eyebrow') }}</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/home.private_label.heading') }}</p>
            <p class="mt-4 text-stone-300">
                {{ __('pages/home.private_label.description') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('private-label') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                {{ __('pages/home.private_label.learn_more') }}
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                {{ __('pages/home.private_label.talk_to_us') }}
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
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/home.news.eyebrow') }}</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/home.news.heading') }}</p>
            </div>
            <a href="{{ route('media') }}" class="group inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                {{ __('pages/home.news.all_link') }} <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
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
