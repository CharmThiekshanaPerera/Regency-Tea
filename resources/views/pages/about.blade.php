@extends('layouts.app')

@section('title', ($page->meta_title ?: 'Who We Are').' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Regency Teas has graded and exported pure Ceylon tea since 1997 — the story, the factory, and the certifications behind every bag.')
@section('og_image', \App\Support\Media::absoluteUrl($page->hero_image_path) ?: asset('images/about/hero.jpg'))

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[70vh] min-h-[480px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/about/hero.jpg') }}" alt="Regency Teas plucker in a Ceylon tea terrace"
         class="absolute inset-0 h-full w-full object-cover animate-kenburns" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-950/40 to-stone-950/10"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            Est. 1997 &middot; Colombo, Sri Lanka
        </span>
        <h1 class="mt-6 max-w-2xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            Who we are
        </h1>
        <p class="mt-5 max-w-xl text-lg text-stone-200">
            Tea specialists who turned a plantation-management background into a pure Ceylon
            tea export house — grading, tasting and packing for the world since 1997.
        </p>
    </div>
</section>

{{-- Story --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Our story</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">From the plantations to the world</p>
            <p class="mt-5 text-stone-600">
                Regency Teas (Pvt) Ltd. was founded in 1997 by a group of tea enthusiasts already
                firmly established in plantation management. Their goal was simple: bring the tea
                loving world a genuinely plantation-fresh cup — and build a company around it.
            </p>
            <p class="mt-4 text-stone-600">
                Since inception, we've packed tea under our flagship brand <strong>Hyleys</strong> —
                classic black and green teas alongside specialty blends built with real fruit and
                herbs. We're one of the few tea exporters in Sri Lanka with our own cold storage
                facility dedicated to those herbs, keeping every flavour note exactly as intended.
            </p>
            <a href="{{ route('brand.show', 'hyleys') }}"
               class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                Explore the Hyleys range <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
        <div class="relative">
            <img src="{{ asset('images/about/story.jpg') }}" alt="Tea pluckers harvesting fresh leaf"
                 class="aspect-[4/5] w-full rounded-2xl object-cover shadow-xl">
            <img src="{{ asset('images/hero/leaf-accent-1.png') }}" alt="" aria-hidden="true"
                 class="pointer-events-none absolute -right-8 -top-8 hidden w-28 sm:block">
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="bg-stone-950 py-16 text-white" data-reveal>
    <div class="mx-auto grid max-w-7xl grid-cols-2 gap-8 px-4 sm:grid-cols-4">
        @foreach ([
            ['to' => now()->year - 1997, 'suffix' => '+', 'label' => 'Years exporting'],
            ['to' => 8000, 'suffix' => '+', 'label' => 'Tea lots graded weekly'],
            ['to' => 500, 'suffix' => '+', 'label' => 'Cups tasted daily'],
            ['to' => 25, 'suffix' => '+', 'label' => 'Export markets'],
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

{{-- Tasting & quality --}}
<section class="overflow-hidden bg-stone-50 py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div class="order-2 lg:order-1">
            <img src="{{ asset('images/about/quality.jpg') }}" alt="Tasting a fresh cup of Ceylon tea"
                 class="aspect-[4/5] w-full rounded-2xl object-cover shadow-xl">
        </div>
        <div class="order-1 lg:order-2">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Graded by taste</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Every batch, tasted before it ships</p>
            <p class="mt-5 text-stone-600">
                Our team of professional tea tasters grades over <strong>8,000 lots of tea every
                week</strong> and tastes more than <strong>500 cups a day</strong> — identifying and
                selecting the right leaf for each market we serve, at the standard our clients
                expect and the price point they need.
            </p>
            <p class="mt-4 text-stone-600">
                Hyleys has also launched a range of functional, benefit-led teas made from 100%
                natural ingredients — special packaging, unique appeal, built for where consumer
                tastes are heading next.
            </p>
        </div>
    </div>
</section>

{{-- Capabilities teaser --}}
<section id="capabilities" class="scroll-mt-[65px] overflow-hidden py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="relative overflow-hidden rounded-2xl bg-stone-950">
            <img src="{{ asset('images/about/capabilities.jpg') }}" alt="High-speed tea bagging machinery"
                 class="absolute inset-0 h-full w-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/80 to-stone-950/30"></div>

            <div class="relative flex flex-col gap-8 px-6 py-14 sm:px-12 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl text-white">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Our capabilities</h2>
                    <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Built to export at scale</p>
                    <p class="mt-4 text-stone-300">
                        90,000 sq ft of production floor, 2.75 million tea bags a day, and
                        staple-free bagging technology from Germany and Italy.
                    </p>
                </div>
                <a href="{{ route('capabilities') }}"
                   class="inline-flex shrink-0 items-center gap-2 rounded-full bg-white px-7 py-3.5 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                    See our capabilities <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Watch --}}
<section class="overflow-hidden bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-5xl px-4 text-center">
        <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Regency Teas, corporate</h2>
        <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Watch our story</p>
        <div class="relative mx-auto mt-8 aspect-video max-w-3xl overflow-hidden rounded-2xl shadow-xl">
            <iframe class="absolute inset-0 h-full w-full"
                    src="https://www.youtube-nocookie.com/embed/WtJg5dlunZo"
                    title="Regency Teas Corporate"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
</section>

{{-- Certifications --}}
<section class="border-y border-stone-200 bg-stone-50 py-16" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <p class="text-center text-sm font-semibold uppercase tracking-widest text-stone-500">
            Certified through the Sri Lanka Tea Board &amp; Bureau Veritas
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
        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-stone-500">
            Good Manufacturing Practice, ISO 22000 and FSSC 22000 (a recognised Global Food Safety
            Initiative body) — plus licensing to manufacture Kosher and Organic certified products.
        </p>
    </div>
</section>

{{-- CTA --}}
<section class="relative overflow-hidden bg-stone-950 py-20 text-white" data-reveal>
    <img src="{{ asset('images/about/craft.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-25">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/90 to-stone-950/60"></div>

    <div class="relative mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-8 px-4">
        <div class="max-w-xl">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Work with us</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Let's talk about your market</p>
            <p class="mt-4 text-stone-300">
                Wholesale, HORECA or private label — our tasters and export team are ready to
                build the right blend for the shelf you're selling on.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('products') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                Browse our teas
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                Talk to us
            </a>
        </div>
    </div>
</section>

@endsection
