@extends('layouts.app')

@section('title', ($page->meta_title ?: 'Our Capabilities').' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'A 90,000 sq ft production plant, 2.75 million tea bags a day, and staple-free bagging technology from Germany and Italy — inside Regency Teas\' manufacturing capability.')
@section('og_image', \App\Support\Media::absoluteUrl($page->hero_image_path) ?: asset('images/capabilities/bagging-line.jpg'))

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[70vh] min-h-[480px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/hero/plantation.jpg') }}" alt="Ceylon tea terraces"
         class="absolute inset-0 h-full w-full object-cover animate-kenburns" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950/90 via-stone-950/45 to-stone-950/10"></div>

    <img src="{{ asset('images/capabilities/teacup.png') }}" alt=""
         aria-hidden="true"
         class="pointer-events-none absolute bottom-0 right-[8%] hidden w-72 drop-shadow-2xl sm:block lg:w-96"
         data-reveal style="--reveal-delay: 200ms">

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            Manufacturing &amp; Export
        </span>
        <h1 class="mt-6 max-w-xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            Our capabilities
        </h1>
        <p class="mt-5 max-w-lg text-lg text-stone-200">
            A modern production plant built to grade, clean, pack and export pure Ceylon tea at
            scale — without compromising the cup.
        </p>
    </div>
</section>

{{-- Stats --}}
<section class="bg-stone-950 py-16 text-white" data-reveal>
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 sm:grid-cols-4">
        @foreach ([
            ['to' => 90000, 'suffix' => '', 'label' => 'Sq ft production plant'],
            ['to' => 250, 'suffix' => '', 'label' => 'Tea bags per minute'],
            ['to' => 2.75, 'suffix' => 'M', 'label' => 'Tea bags per day'],
            ['to' => 3500, 'suffix' => '', 'label' => 'Metric tons / year'],
        ] as $i => $stat)
            <div class="text-center sm:text-left" style="--reveal-delay: {{ $i * 100 }}ms">
                <p class="text-4xl font-semibold tracking-tight text-emerald-400 sm:text-5xl">
                    <span data-count-to="{{ $stat['to'] }}" data-count-suffix="{{ $stat['suffix'] }}">0{{ $stat['suffix'] }}</span>
                </p>
                <p class="mt-1 text-xs uppercase tracking-widest text-stone-400 sm:text-sm">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Tea cleaning technology --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Purity first</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Cleaned, before it's packed</p>
            <p class="mt-5 text-stone-600">
                Regency Teas operates a production plant of <strong>90,000 square feet</strong> with
                modern infrastructure and dedicated tea cleaning technology. That cleaning stage is
                a vital part of the manufacturing process — impurities are thoroughly removed and
                the leaf purified before it ever reaches a consumer's cup.
            </p>
            <p class="mt-4 text-stone-600">
                Every lot that enters our plant goes through the same standard, regardless of
                destination market or order size.
            </p>
        </div>
        <div class="relative">
            <img src="{{ asset('images/about/story.jpg') }}" alt="Freshly harvested Ceylon tea leaf"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl">
        </div>
    </div>
</section>

{{-- Bagging technology --}}
<section class="overflow-hidden bg-stone-50 py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div class="order-2 lg:order-1 relative">
            <img src="{{ asset('images/capabilities/bagging-line.jpg') }}" alt="High-speed tea bagging production line"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl">
        </div>
        <div class="order-1 lg:order-2">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Bagging technology</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">250 bags a minute, staple-free</p>
            <p class="mt-5 text-stone-600">
                The Company is equipped with a range of high-speed tea bagging machines capable of
                producing up to <strong>250 tea bags per minute</strong> — a combined capacity of
                <strong>2.75 million tea bags a day</strong>. Our latest machines, sourced from
                Germany and Italy, use proprietary technology to seal every tea bag
                <strong>without aluminium staples</strong>, alongside micro-filter Pyramid lines.
            </p>
            <a href="{{ route('about') }}#capabilities"
               class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                More about who we are <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>

{{-- Certifications --}}
<section class="border-y border-stone-200 bg-white py-16" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <p class="text-center text-sm font-semibold uppercase tracking-widest text-stone-500">
            Every batch backed by certification
        </p>
        <div class="mt-8 grid grid-cols-2 items-center gap-8 sm:grid-cols-4">
            @foreach ([
                ['file' => 'iso-22000.png', 'label' => 'ISO 22000'],
                ['file' => 'fssc-22000.png', 'label' => 'FSSC 22000 (GFSI)'],
                ['file' => 'usda-organic.png', 'label' => 'Organic licensed'],
                ['file' => 'halal.png', 'label' => 'Kosher licensed'],
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
    <img src="{{ asset('images/about/craft.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-25">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/90 to-stone-950/60"></div>

    <div class="relative mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-8 px-4">
        <div class="max-w-xl">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Have a spec in mind?</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Tell us what you need to produce</p>
            <p class="mt-4 text-stone-300">
                Pack format, volume, certification requirements — our production team can tell you
                what's possible before you commit to an order.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('private-label') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                Private label
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                Talk to us
            </a>
        </div>
    </div>
</section>

@endsection
