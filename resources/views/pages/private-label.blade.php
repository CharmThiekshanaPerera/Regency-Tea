@extends('layouts.app')

@section('title', ($page->meta_title ?: 'Private Label').' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Private-label Ceylon tea manufacturing — blending, packing, branding and export documentation from Regency Teas.')
@section('og_image', \App\Support\Media::absoluteUrl($page->hero_image_path) ?: asset('images/sections/ingredients.jpg'))

@php
$clients = ['chelton-1', 'royal-tea', 'albred-1', 'club-cave-1', 'filicori-1', 'whs-1', 'kwinst-1', 'flecha', 'jw', 'e-mart', 'eko', 'e-lia', 'dori-new', 'iumi', 'montana', 'tasi', 'vanua', 'al-ghaith'];

$steps = [
    ['title' => 'Blend development', 'body' => 'Our tea tasters build a blend to your target profile, price point and market.'],
    ['title' => 'Packaging', 'body' => 'Tea bags, foil envelopes, pyramid bags, loose leaf, tins, cartons and pouches.'],
    ['title' => 'Branding', 'body' => 'Your artwork, our production. Full design support if you need it.'],
    ['title' => 'Export', 'body' => 'Documentation, certification and logistics handled from Colombo.'],
];
@endphp

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[70vh] min-h-[480px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/sections/ingredients.jpg') }}" alt="Loose Ceylon tea blends ready for private-label packing"
         class="absolute inset-0 h-full w-full object-cover animate-kenburns" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950/95 via-stone-950/75 to-stone-950/25"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/60 via-transparent to-transparent"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            Private Label &amp; Contract Packing
        </span>
        <h1 class="mt-6 max-w-2xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            Your brand, our teas
        </h1>
        <p class="mt-5 max-w-xl text-lg text-stone-200">
            {{ $page->excerpt ?: 'Contract packing of tea for private labels is an integral part of our business, and we\'re happy to entertain your project — from first blend to final export documentation.' }}
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('contact') }}"
               class="rounded-full bg-emerald-600 px-7 py-3 font-semibold text-white shadow-lg shadow-emerald-900/40 transition hover:-translate-y-0.5 hover:bg-emerald-500">
                Start a project
            </a>
            <a href="#process" class="rounded-full border border-white/30 bg-white/5 px-7 py-3 font-semibold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15">
                See how it works
            </a>
        </div>
    </div>
</section>

{{-- Trusted by — infinite logo marquee --}}
<section class="border-b border-stone-200 bg-white py-12" data-reveal>
    <p class="text-center text-sm font-semibold uppercase tracking-widest text-stone-500">
        Trusted by private-label brands worldwide
    </p>
    <div class="relative mt-8 overflow-hidden">
        <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-16 bg-gradient-to-r from-white to-transparent"></div>
        <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-16 bg-gradient-to-l from-white to-transparent"></div>

        <div class="flex w-max animate-marquee items-center gap-14">
            @foreach ([1, 2] as $pass)
                @foreach ($clients as $client)
                    <img src="{{ asset('images/clients/'.$client.'.png') }}" alt=""
                         class="h-14 w-auto shrink-0 object-contain opacity-70 grayscale transition hover:opacity-100 hover:grayscale-0"
                         loading="lazy">
                @endforeach
            @endforeach
        </div>
    </div>
</section>

{{-- Why private label with us --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Why Regency Teas</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Built for private label from day one</p>
            <p class="mt-5 text-stone-600">
                Contract packing for private labels has been part of our business since the
                beginning — not a side project bolted onto a retail brand. Our tasters, our
                90,000 sq ft plant and our certifications exist to make your label look and taste
                exactly the way you specified.
            </p>
            <ul role="list" class="mt-6 space-y-3">
                @foreach ([
                    'Certified through the Sri Lanka Tea Board & Bureau Veritas — GMP, ISO 22000, FSSC 22000',
                    'Licensed for Kosher and Organic certified production',
                    'Own cold storage for herbs & flavourings — consistent batch to batch',
                    '2.75 million tea bags/day capacity across staple-free & pyramid lines',
                ] as $point)
                    <li class="flex items-start gap-3 text-stone-700">
                        <svg class="mt-1 h-4 w-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm">{{ $point }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="relative">
            <img src="{{ asset('images/private-label/why-us.png') }}" alt="Hyleys private-label packaging formats — tins, canisters and cartons"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl">
        </div>
    </div>
</section>

{{-- Process --}}
<section id="process" class="scroll-mt-[65px] bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">How it works</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">From brief to shipped</p>
        </div>

        <ul role="list" class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($steps as $i => $step)
                <li class="group relative flex h-full flex-col rounded-xl border border-stone-200 bg-white p-6 transition duration-300 hover:-translate-y-1 hover:border-emerald-600 hover:shadow-lg"
                    style="--reveal-delay: {{ $i * 100 }}ms">
                    <span class="text-3xl font-semibold text-emerald-700/20 transition group-hover:text-emerald-700/40">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <h3 class="mt-3 text-lg font-medium">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm text-stone-600">{{ $step['body'] }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- Certifications --}}
<section class="border-y border-stone-200 bg-white py-16" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <p class="text-center text-sm font-semibold uppercase tracking-widest text-stone-500">
            Certified quality on every private-label run
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
                         class="h-24 w-auto object-contain transition duration-300 hover:scale-110 sm:h-28" loading="lazy">
                    <span class="text-xs font-medium text-stone-500">{{ $badge['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative overflow-hidden bg-stone-950 py-20 text-white" data-reveal>
    <img src="{{ asset('images/sections/ingredients.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-20">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/92 to-stone-950/70"></div>

    <div class="relative mx-auto max-w-3xl px-4 text-center">
        <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Start a private-label project</h2>
        <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Tell us your market, volume and format</p>
        <p class="mx-auto mt-4 max-w-xl text-stone-300">
            Send us your target market, volumes and packaging format, and we'll come back with a
            proposal built around what you actually need.
        </p>
        <a href="{{ route('contact') }}"
           class="mt-8 inline-block rounded-full bg-white px-8 py-3.5 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
            Talk to us
        </a>
    </div>
</section>

@endsection
