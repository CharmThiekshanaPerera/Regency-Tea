@extends('layouts.app')

@section('title', ($page->meta_title ?: 'Who We Are').' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Regency Teas has graded and exported pure Ceylon tea since 1997 — the story, the factory, and the certifications behind every bag.')
@section('og_image', \App\Support\Media::absoluteUrl($page->hero_image_path) ?: asset('images/about/hero.jpg'))

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[70vh] min-h-[480px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/about/hero.jpg') }}" alt="{{ __('pages/about.hero.image_alt') }}"
         class="absolute inset-0 h-full w-full object-cover animate-kenburns" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-950/40 to-stone-950/10"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            {{ __('pages/about.hero.badge') }}
        </span>
        <h1 class="mt-6 max-w-2xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            {{ __('pages/about.hero.heading') }}
        </h1>
        <p class="mt-5 max-w-xl text-lg text-stone-200">
            {{ __('pages/about.hero.description') }}
        </p>
    </div>
</section>

{{-- Story --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/about.story.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/about.story.heading') }}</p>
            <p class="mt-5 text-stone-600">
                {{ __('pages/about.story.paragraph_1') }}
            </p>
            <p class="mt-4 text-stone-600">
                {!! __('pages/about.story.paragraph_2') !!}
            </p>
            <a href="{{ route('brand.show', 'hyleys') }}"
               class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-emerald-800 hover:underline">
                {{ __('pages/about.story.cta_label') }} <span aria-hidden="true">&rarr;</span>
            </a>
        </div>
        <div class="relative">
            <img src="{{ asset('images/about/story.jpg') }}" alt="{{ __('pages/about.story.image_alt') }}"
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
            ['to' => now()->year - 1997, 'suffix' => '+', 'label' => __('pages/about.stats.years_exporting')],
            ['to' => 8000, 'suffix' => '+', 'label' => __('pages/about.stats.lots_graded')],
            ['to' => 500, 'suffix' => '+', 'label' => __('pages/about.stats.cups_tasted')],
            ['to' => 35, 'suffix' => '+', 'label' => __('pages/about.stats.export_markets')],
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
            <img src="{{ asset('images/about/quality.jpg') }}" alt="{{ __('pages/about.quality.image_alt') }}"
                 class="aspect-[4/5] w-full rounded-2xl object-cover shadow-xl">
        </div>
        <div class="order-1 lg:order-2">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/about.quality.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/about.quality.heading') }}</p>
            <p class="mt-5 text-stone-600">
                {!! __('pages/about.quality.paragraph_1') !!}
            </p>
            <p class="mt-4 text-stone-600">
                {{ __('pages/about.quality.paragraph_2') }}
            </p>
        </div>
    </div>
</section>

{{-- Capabilities teaser --}}
<section id="capabilities" class="scroll-mt-[65px] overflow-hidden py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="relative overflow-hidden rounded-2xl bg-stone-950">
            <img src="{{ asset('images/about/capabilities.jpg') }}" alt="{{ __('pages/about.capabilities.image_alt') }}"
                 class="absolute inset-0 h-full w-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/80 to-stone-950/30"></div>

            <div class="relative flex flex-col gap-8 px-6 py-14 sm:px-12 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl text-white">
                    <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">{{ __('pages/about.capabilities.eyebrow') }}</h2>
                    <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/about.capabilities.heading') }}</p>
                    <p class="mt-4 text-stone-300">
                        {{ __('pages/about.capabilities.paragraph') }}
                    </p>
                </div>
                <a href="{{ route('capabilities') }}"
                   class="inline-flex shrink-0 items-center gap-2 rounded-full bg-white px-7 py-3.5 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                    {{ __('pages/about.capabilities.cta_label') }} <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Watch --}}
<section class="overflow-hidden bg-stone-50 py-20" data-reveal>
    <div class="mx-auto max-w-5xl px-4 text-center">
        <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/about.watch.eyebrow') }}</h2>
        <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/about.watch.heading') }}</p>
        <div class="relative mx-auto mt-8 aspect-video max-w-3xl overflow-hidden rounded-2xl shadow-xl">
            <iframe class="absolute inset-0 h-full w-full"
                    src="https://www.youtube-nocookie.com/embed/WtJg5dlunZo"
                    title="{{ __('pages/about.watch.iframe_title') }}"
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
            {{ __('pages/about.certifications.heading') }}
        </p>
        <div class="mt-8 grid grid-cols-2 items-center gap-8 sm:grid-cols-4">
            @foreach ([
                ['file' => 'iso-22000.png', 'label' => __('pages/about.certifications.badges.iso_22000')],
                ['file' => 'fssc-22000.png', 'label' => __('pages/about.certifications.badges.fssc_22000')],
                ['file' => 'usda-organic.png', 'label' => __('pages/about.certifications.badges.organic')],
                ['file' => 'halal.png', 'label' => __('pages/about.certifications.badges.kosher')],
            ] as $badge)
                <div class="flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('images/badges/'.$badge['file']) }}" alt="{{ $badge['label'] }}"
                         class="h-24 w-auto object-contain transition duration-300 hover:scale-110 sm:h-28" loading="lazy">
                    <span class="text-xs font-medium text-stone-500">{{ $badge['label'] }}</span>
                </div>
            @endforeach
        </div>
        <p class="mx-auto mt-8 max-w-2xl text-center text-sm text-stone-500">
            {{ __('pages/about.certifications.paragraph') }}
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
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">{{ __('pages/about.cta.eyebrow') }}</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/about.cta.heading') }}</p>
            <p class="mt-4 text-stone-300">
                {{ __('pages/about.cta.paragraph') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('products') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                {{ __('pages/about.cta.browse_label') }}
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                {{ __('pages/about.cta.contact_label') }}
            </a>
        </div>
    </div>
</section>

@endsection
