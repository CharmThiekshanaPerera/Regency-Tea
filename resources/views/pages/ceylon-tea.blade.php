@extends('layouts.app')

@section('title', ($page->meta_title ?: __('pages/ceylon-tea.meta.title')).' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: __('pages/ceylon-tea.meta.description'))
@section('og_image', \App\Support\Media::absoluteUrl($page->hero_image_path) ?: asset('images/ceylon-tea/hero.jpg'))

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[70vh] min-h-[480px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/ceylon-tea/hero.jpg') }}" alt="{{ __('pages/ceylon-tea.hero.image_alt') }}"
         class="absolute inset-0 h-full w-full object-cover animate-kenburns" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-950/40 to-stone-950/10"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            {{ __('pages/ceylon-tea.hero.badge') }}
        </span>
        <h1 class="mt-6 max-w-2xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            {{ __('pages/ceylon-tea.hero.heading') }}
        </h1>
        <p class="mt-5 max-w-xl text-lg text-stone-200">
            {{ __('pages/ceylon-tea.hero.paragraph') }}
        </p>
    </div>
</section>

{{-- History --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div class="order-2 lg:order-1">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/ceylon-tea.history.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/ceylon-tea.history.heading') }}</p>
            <p class="mt-5 text-stone-600">
                {{ __('pages/ceylon-tea.history.paragraph_1') }}
            </p>
            <p class="mt-4 text-stone-600">
                {{ __('pages/ceylon-tea.history.paragraph_2') }}
            </p>
        </div>
        <div class="order-1 lg:order-2 relative">
            <img src="{{ asset('images/ceylon-tea/history.jpg') }}"
                 alt="{{ __('pages/ceylon-tea.history.image_alt') }}"
                 class="aspect-[4/5] w-full rounded-2xl object-cover shadow-xl sepia-[.2]">
        </div>
    </div>
</section>

{{-- Quick facts --}}
<section class="bg-stone-950 py-20 text-white" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">{{ __('pages/ceylon-tea.facts.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/ceylon-tea.facts.heading') }}</p>
        </div>

        <ul role="list" class="mt-12 grid gap-6 sm:grid-cols-2">
            @foreach ([
                ['year' => __('pages/ceylon-tea.facts.items.0.year'), 'icon' => 'icon-factory.png', 'text' => __('pages/ceylon-tea.facts.items.0.text')],
                ['year' => __('pages/ceylon-tea.facts.items.1.year'), 'icon' => 'icon-auction.png', 'text' => __('pages/ceylon-tea.facts.items.1.text')],
                ['year' => __('pages/ceylon-tea.facts.items.2.year'), 'icon' => 'icon-tasting.png', 'text' => __('pages/ceylon-tea.facts.items.2.text')],
                ['year' => __('pages/ceylon-tea.facts.items.3.year'), 'icon' => 'icon-export.png', 'text' => __('pages/ceylon-tea.facts.items.3.text')],
            ] as $i => $fact)
                <li class="flex items-center gap-5 rounded-xl border border-white/10 bg-white/5 p-6" style="--reveal-delay: {{ $i * 100 }}ms">
                    <img src="{{ asset('images/ceylon-tea/'.$fact['icon']) }}" alt=""
                         class="h-16 w-16 shrink-0 rounded-full bg-white/90 p-2.5" loading="lazy">
                    <div>
                        <span class="text-xl font-semibold text-emerald-400">{{ $fact['year'] }}</span>
                        <p class="mt-1 text-sm text-stone-300">{{ $fact['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- Terroir / character --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/ceylon-tea.terroir.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/ceylon-tea.terroir.heading') }}</p>
            <p class="mt-5 text-stone-600">
                {{ __('pages/ceylon-tea.terroir.paragraph_1') }}
            </p>
            <p class="mt-4 text-stone-600">
                {{ __('pages/ceylon-tea.terroir.paragraph_2') }}
            </p>
        </div>
        <div class="relative">
            <img src="{{ asset('images/hero/plantation.jpg') }}" alt="{{ __('pages/ceylon-tea.terroir.image_alt') }}"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl">
        </div>
    </div>
</section>

{{-- Symbol of quality --}}
<section class="border-y border-stone-200 bg-stone-50 py-20" data-reveal>
    <div class="mx-auto flex max-w-5xl flex-col items-center gap-8 px-4 text-center lg:flex-row lg:text-left">
        <img src="{{ asset('images/ceylon-tea/lion-mark.png') }}" alt="{{ __('pages/ceylon-tea.quality.image_alt') }}"
             class="h-32 w-auto shrink-0 drop-shadow-md">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/ceylon-tea.quality.eyebrow') }}</h2>
            <p class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">{{ __('pages/ceylon-tea.quality.heading') }}</p>
            <p class="mt-4 text-stone-600">
                {{ __('pages/ceylon-tea.quality.paragraph') }}
            </p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative overflow-hidden bg-stone-950 py-20 text-white" data-reveal>
    <img src="{{ asset('images/ceylon-tea/hero.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-25">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/90 to-stone-950/60"></div>

    <div class="relative mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-8 px-4">
        <div class="max-w-xl">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">{{ __('pages/ceylon-tea.cta.eyebrow') }}</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/ceylon-tea.cta.heading') }}</p>
            <p class="mt-4 text-stone-300">
                {{ __('pages/ceylon-tea.cta.paragraph') }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ranges') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                {{ __('pages/ceylon-tea.cta.browse_ranges') }}
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                {{ __('pages/ceylon-tea.cta.talk_to_us') }}
            </a>
        </div>
    </div>
</section>

@endsection
