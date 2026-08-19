@extends('layouts.app')
@section('title', __('pages/health-benefits.meta.title').config('regency.company.name'))
@section('meta_description', $page->meta_description ?: __('pages/health-benefits.meta.description'))
@section('og_image', asset('images/health-benefits/heart-health.jpg'))

@php
$tips = [
    [
        'title' => __('pages/health-benefits.tips.items.0.title'),
        'body'  => __('pages/health-benefits.tips.items.0.body'),
        'image' => 'images/health-benefits/antioxidants.jpg',
    ],
    [
        'title' => __('pages/health-benefits.tips.items.1.title'),
        'body'  => __('pages/health-benefits.tips.items.1.body'),
        'image' => 'images/health-benefits/l-theanine.jpg',
    ],
    [
        'title' => __('pages/health-benefits.tips.items.2.title'),
        'body'  => __('pages/health-benefits.tips.items.2.body'),
        'image' => 'images/health-benefits/heart-health.jpg',
    ],
    [
        'title' => __('pages/health-benefits.tips.items.3.title'),
        'body'  => __('pages/health-benefits.tips.items.3.body'),
        'image' => 'images/health-benefits/fluoride.jpg',
    ],
    [
        'title' => __('pages/health-benefits.tips.items.4.title'),
        'body'  => __('pages/health-benefits.tips.items.4.body'),
        'image' => 'images/health-benefits/weight-loss.jpg',
    ],
];
@endphp

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[42vh] min-h-[320px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/hero/plantation.jpg') }}" alt="{{ __('pages/health-benefits.hero.image_alt') }}"
         class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/95 via-stone-950/70 to-stone-950/40"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            {{ __('pages/health-benefits.hero.badge') }}
        </span>
        <h1 class="mt-6 max-w-xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            {{ __('pages/health-benefits.hero.heading') }}
        </h1>
    </div>
</section>

{{-- Intro --}}
<section class="overflow-hidden py-16" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <p class="text-stone-600">
                {{ __('pages/health-benefits.intro.paragraph_1') }}
            </p>
            <p class="mt-4 text-stone-600">
                {{ __('pages/health-benefits.intro.paragraph_2') }}
            </p>
        </div>
        <div class="relative mx-auto w-full max-w-sm">
            <img src="{{ asset('images/health-benefits/hero-accent.png') }}" alt="{{ __('pages/health-benefits.intro.image_alt') }}"
                 class="w-full drop-shadow-xl">
        </div>
    </div>
</section>

{{-- Health tips --}}
<section class="bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/health-benefits.tips.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/health-benefits.tips.heading') }}</p>
        </div>

        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($tips as $i => $tip)
                <div class="group overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                     style="--reveal-delay: {{ $i * 100 }}ms">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset($tip['image']) }}" alt="{{ $tip['title'] }}"
                             class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    </div>
                    <div class="p-6">
                        <h3 class="text-lg font-medium">{{ $tip['title'] }}</h3>
                        <p class="mt-2 text-sm text-stone-600">{{ $tip['body'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="border-t border-stone-200 bg-white py-14">
    <div class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-6 px-4">
        <p class="text-lg font-medium">{{ __('pages/health-benefits.cta.heading') }}</p>
        <x-quote-button />
    </div>
</section>

@endsection
