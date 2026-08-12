@props(['stats' => []])

@php
$slides = [
    ['image' => 'images/hero/plantation.jpg', 'focus' => 'center'],
    ['image' => 'images/hero/sunset-valley.jpg', 'focus' => 'center'],
    ['image' => 'images/hero/tea-cup.jpg', 'focus' => 'center'],
];
@endphp

<section
    x-data="{
        active: 0,
        n: {{ count($slides) }},
        timer: null,
        start() { if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) { this.timer = setInterval(() => this.active = (this.active + 1) % this.n, 6000) } },
        stop() { clearInterval(this.timer) },
    }"
    x-init="start()"
    @mouseenter="stop()" @mouseleave="start()" @focusin="stop()" @focusout="start()"
    class="relative isolate flex h-[92vh] min-h-[600px] max-h-[820px] items-center overflow-hidden bg-stone-950"
>
    {{-- Background slides --}}
    @foreach ($slides as $i => $slide)
        <div class="absolute inset-0 transition-opacity duration-[1400ms] ease-in-out"
             :class="active === {{ $i }} ? 'opacity-100' : 'opacity-0'"
             aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
            <img src="{{ asset($slide['image']) }}" alt=""
                 class="h-full w-full object-cover animate-kenburns"
                 @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif>
        </div>
    @endforeach

    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/95 via-stone-950/55 to-stone-950/10"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950/80 via-stone-950/20 to-transparent"></div>

    <img src="{{ asset('images/hero/leaf-accent-1.png') }}" alt="" aria-hidden="true"
         class="pointer-events-none absolute -right-6 top-16 hidden w-36 opacity-80 lg:block animate-kenburns"
         style="animation-duration: 26s">

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6">
        <div class="max-w-2xl text-white" data-reveal>
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
                Pure Ceylon Tea Exporter &middot; Since 1997
            </span>

            <h1 class="mt-6 text-4xl font-semibold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">
                Ceylon tea, <span class="text-emerald-400">grown &amp; graded</span> for the world
            </h1>

            <p class="mt-6 max-w-xl text-lg text-stone-200">
                From the hill country to your shelf — four trusted brands, hundreds of blends,
                and a factory built to deliver export-grade tea at scale.
            </p>

            <div class="mt-9 flex flex-wrap gap-4">
                <a href="{{ route('products') }}"
                   class="rounded-full bg-emerald-600 px-8 py-3.5 font-semibold text-white shadow-lg shadow-emerald-900/40 transition hover:-translate-y-0.5 hover:bg-emerald-500">
                    Explore our teas
                </a>
                <a href="{{ route('contact') }}"
                   class="rounded-full border border-white/30 bg-white/5 px-8 py-3.5 font-semibold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15">
                    Request a quote
                </a>
            </div>
        </div>
    </div>

    {{-- Slide dots --}}
    <div class="absolute bottom-8 left-1/2 z-10 flex -translate-x-1/2 gap-2">
        @foreach ($slides as $i => $slide)
            <button type="button" @click="stop(); active = {{ $i }}"
                    class="h-2 rounded-full transition-all duration-300"
                    :class="active === {{ $i }} ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/70'"
                    aria-label="Show slide {{ $i + 1 }}"></button>
        @endforeach
    </div>

    {{-- Scroll cue --}}
    <a href="#below-hero"
       class="absolute bottom-8 right-8 z-10 hidden animate-bounce items-center justify-center rounded-full border border-white/30 p-3 text-white/80 transition hover:border-white hover:text-white sm:flex"
       aria-label="Scroll to content">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </a>
</section>
