@extends('layouts.app')
@section('title', 'Health Benefits of Tea — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'The health benefits of drinking tea — antioxidants, L-theanine, heart health and more, from Regency Teas.')
@section('og_image', asset('images/health-benefits/heart-health.jpg'))

@php
$tips = [
    [
        'title' => 'Antioxidants for overall wellbeing',
        'body'  => "Antioxidants protect the body from damage caused by harmful molecules called free radicals — a factor in the development of blood vessel disease, cancer and other conditions. Tea contains unique antioxidants called flavonoids, the most potent of which, EGCG, may help guard against free radicals that contribute to cancer, heart disease and clogged arteries.",
        'image' => 'images/health-benefits/antioxidants.jpg',
    ],
    [
        'title' => 'L-theanine can help you focus',
        'body'  => "Tea's key active ingredient is caffeine — a known stimulant, though far gentler than coffee. It also contains the amino acid L-theanine, which crosses the blood-brain barrier and increases the activity of an inhibitory neurotransmitter with anti-anxiety effects. Studies show caffeine and L-theanine have synergistic effects, making the combination particularly effective at improving focus.",
        'image' => 'images/health-benefits/l-theanine.jpg',
    ],
    [
        'title' => 'Supports heart health',
        'body'  => "Cardiovascular disease is one of the leading causes of death worldwide. Regular tea drinking has been shown to improve several of its main risk factors, including total cholesterol, LDL cholesterol and triglycerides. Catechins in green tea and theaflavins in black tea can inhibit lipid oxidation and plaque formation while reducing cholesterol — regular tea drinkers show up to a 31% lower risk of cardiovascular disease.",
        'image' => 'images/health-benefits/heart-health.jpg',
    ],
    [
        'title' => 'Naturally high in fluoride',
        'body'  => "The Camellia sinensis plant absorbs fluoride from the soil, and brewing extracts most of it from the leaves. A daily cup can contribute meaningfully to your fluoride intake, helping reduce tooth decay — it even shifts the pH in your mouth, which can help prevent cavities, while polyphenols in tea may inhibit the bacteria that cause decay.",
        'image' => 'images/health-benefits/fluoride.jpg',
    ],
    [
        'title' => 'Zero calories, real hydration',
        'body'  => "Tea in its pure, unadulterated form contains no calories, making it a versatile alternative to water — hot or cold, with endless options for flavour. Research also shows tea can boost metabolic rate, and several studies link regular tea drinking to decreases in body fat, body weight and waist circumference.",
        'image' => 'images/health-benefits/weight-loss.jpg',
    ],
];
@endphp

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[42vh] min-h-[320px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/hero/plantation.jpg') }}" alt="Ceylon tea plantation"
         class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/95 via-stone-950/70 to-stone-950/40"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            Health Benefits
        </span>
        <h1 class="mt-6 max-w-xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            The health benefits of drinking tea
        </h1>
    </div>
</section>

{{-- Intro --}}
<section class="overflow-hidden py-16" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <p class="text-stone-600">
                Tea is a name given to a lot of brews, but more commonly refers to the aromatic
                beverage prepared by pouring boiling water over cured leaves of the Camellia
                sinensis plant. The term herbal tea usually refers to infusions of fruit or herbs
                made without the tea plant — steeps of rosehip, chamomile or rooibos, sometimes
                called tisanes to avoid confusion with tea made from the tea plant itself.
            </p>
            <p class="mt-4 text-stone-600">
                Regardless of the season, tea is a refreshing beverage that can be served hot or
                cold. Its benefits go well past refreshment, though — the flavonoids, antioxidants
                and polyphenolic compounds in tea have made it a popular remedy for a wide variety
                of ailments, and it can even be used in topical applications for certain issues.
            </p>
        </div>
        <div class="relative mx-auto w-full max-w-sm">
            <img src="{{ asset('images/health-benefits/hero-accent.png') }}" alt="Tea with honey and lemon"
                 class="w-full drop-shadow-xl">
        </div>
    </div>
</section>

{{-- Health tips --}}
<section class="bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Health tips</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Five reasons to reach for another cup</p>
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
        <p class="text-lg font-medium">Want to bring these teas to your shelf?</p>
        <x-quote-button />
    </div>
</section>

@endsection
