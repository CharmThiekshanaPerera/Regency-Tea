@props([])

@php
/**
 * Backgrounds are sampled from each product's own packaging (not a fixed
 * palette), and `case`/`weight` come from the slide data rather than a
 * hard-coded uppercase transform — slide 2 is a real Sinhala product name
 * ("අපේම කහට", printed on the pack itself) and reads wrong in caps.
 */
$slides = [
    [
        'brand'    => 'Lakma',
        'color'    => '#7C6BB0',
        'photo'    => 'images/hero/tea-cup.jpg',
        'heading'  => 'Lavender Dreams',
        'sub'      => 'Green tea with lavender & honey, for a calmer cup.',
        'case'     => 'uppercase',
        'weight'   => 800,
        'lang'     => 'en',
        'pack'     => 'images/curved-hero/lavender-dreams.png',
        'family'   => null,
        'cta'      => ['label' => 'Shop Lakma', 'url' => route('brand.show', 'lakma')],
    ],
    [
        'brand'    => 'Truly Ceylon',
        'color'    => '#D98F1E',
        'photo'    => 'images/hero/plantation.jpg',
        'heading'  => 'අපේම කහට',
        'sub'      => 'SMAK · Truly Ceylon black tea, straight from the hills.',
        'case'     => 'sentence',
        'weight'   => 400,
        'lang'     => 'si',
        'pack'     => null,
        'family'   => [
            'images/curved-hero/apema-20g.png',
            'images/curved-hero/apema-50g.png',
            'images/curved-hero/apema-100g.png',
            'images/curved-hero/apema-200g.png',
        ],
        'cta'      => ['label' => 'Shop Truly Ceylon', 'url' => route('brand.show', 'truly-ceylon')],
    ],
    [
        'brand'    => 'Hyleys',
        'color'    => '#EFC23E',
        'photo'    => 'images/hero/sunset-valley.jpg',
        'heading'  => 'Organic Chamomile Tea',
        'sub'      => 'Caffeine-free, 100% natural, USDA Organic certified.',
        'case'     => 'uppercase',
        'weight'   => 800,
        'lang'     => 'en',
        'pack'     => 'images/curved-hero/chamomile.png',
        'family'   => null,
        'cta'      => ['label' => 'Shop Hyleys', 'url' => route('brand.show', 'hyleys')],
    ],
];

$brands = \App\Models\Brand::orderBy('sort')->get();

$brandMarks = [
    'hyleys'       => 'images/curved-hero/marks/hyleys.png',
    'lakma'        => 'images/curved-hero/marks/lakma.png',
    'truly-ceylon' => 'images/curved-hero/marks/truly-ceylon.png',
    'dr-tea'       => 'images/curved-hero/marks/dr-tea.png',
];
@endphp

<section
    x-data="{
        active: 0,
        n: {{ count($slides) }},
        timer: null,
        start() { if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) { this.timer = setInterval(() => this.next(), 7000) } },
        stop() { clearInterval(this.timer) },
        next() { this.active = (this.active + 1) % this.n },
        prev() { this.active = (this.active - 1 + this.n) % this.n },
    }"
    x-init="start()"
    @mouseenter="stop()" @mouseleave="start()" @focusin="stop()" @focusout="start()"
    class="relative isolate overflow-hidden"
>
    <svg width="0" height="0" class="absolute" aria-hidden="true">
        <defs>
            <clipPath id="curved-divider" clipPathUnits="objectBoundingBox">
                <path d="M0.35,0 C0.63,0.18 0.63,0.82 0.53,1 L1,1 L1,0 Z" />
            </clipPath>
        </defs>
    </svg>

    <div class="relative h-[calc(100vh-73px)] min-h-[560px]">
        @foreach ($slides as $i => $slide)
            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                 :class="active === {{ $i }} ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
                 aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">

                {{-- Flat colour block, sampled from this product's packaging --}}
                <div class="absolute inset-0" style="background-color: {{ $slide['color'] }}"></div>

                {{-- Photo, clipped to the curved divider --}}
                <img src="{{ asset($slide['photo']) }}" alt=""
                     class="absolute inset-0 h-full w-full object-cover"
                     style="clip-path: url(#curved-divider)"
                     @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif>

                {{-- Text — each element flies in on its own delay whenever this slide becomes active,
                     and every time it cycles back around, like a PowerPoint staggered entrance. --}}
                <div class="relative z-10 flex h-full items-center">
                    <div class="max-w-md px-6 sm:px-10 lg:px-14" style="color: {{ $slide['case'] === 'sentence' ? '#2b2110' : '#ffffff' }}">
                        <span x-show="active === {{ $i }}"
                              x-transition:enter="transition ease-out duration-500"
                              x-transition:enter-start="opacity-0 -translate-y-3"
                              x-transition:enter-end="opacity-100 translate-y-0"
                              x-transition:leave="transition ease-in duration-200"
                              x-transition:leave-end="opacity-0"
                              class="inline-block text-xs font-semibold uppercase tracking-widest opacity-80">
                            {{ $slide['brand'] }}
                        </span>

                        <h2 x-show="active === {{ $i }}"
                            x-transition:enter="transition ease-out duration-700 delay-150"
                            x-transition:enter-start="opacity-0 translate-y-8"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-end="opacity-0"
                            @if ($slide['lang'] !== 'en') lang="{{ $slide['lang'] }}" @endif
                            @class([
                                'mt-3 text-3xl sm:text-4xl leading-tight',
                                'uppercase tracking-tight' => $slide['case'] === 'uppercase',
                            ])
                            style="font-weight: {{ $slide['weight'] }}; {{ $slide['lang'] === 'si' ? "font-family: 'Noto Sans Sinhala', sans-serif;" : '' }}">
                            {{ $slide['heading'] }}
                        </h2>

                        <p x-show="active === {{ $i }}"
                           x-transition:enter="transition ease-out duration-700 delay-300"
                           x-transition:enter-start="opacity-0 translate-y-8"
                           x-transition:enter-end="opacity-100 translate-y-0"
                           x-transition:leave="transition ease-in duration-200"
                           x-transition:leave-end="opacity-0"
                           class="mt-4 max-w-xs text-sm sm:text-base" style="opacity: .9">
                            {{ $slide['sub'] }}
                        </p>

                        <a x-show="active === {{ $i }}"
                           x-transition:enter="transition ease-out duration-500 delay-500"
                           x-transition:enter-start="opacity-0 translate-y-4 scale-90"
                           x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                           x-transition:leave="transition ease-in duration-200"
                           x-transition:leave-end="opacity-0"
                           href="{{ $slide['cta']['url'] }}"
                           class="mt-7 inline-block rounded-full px-6 py-3 text-sm font-semibold shadow-lg transition-colors hover:-translate-y-0.5"
                           style="background-color: {{ $slide['case'] === 'sentence' ? '#2b2110' : '#ffffff' }}; color: {{ $slide['case'] === 'sentence' ? '#ffffff' : $slide['color'] }}">
                            {{ $slide['cta']['label'] }}
                        </a>
                    </div>
                </div>

                {{-- Pack shot(s), straddling the curve — cascades in piece by piece --}}
                @if ($slide['family'])
                    <div class="absolute inset-y-0 hidden sm:block" style="left: 30%; right: 4%;">
                        @foreach ($slide['family'] as $j => $pack)
                            @php
                                $count = count($slide['family']);
                                $scale = 0.55 + ($j / ($count - 1)) * 0.45;
                                $delayClass = ['delay-150', 'delay-300', 'delay-500', 'delay-700'][$j] ?? 'delay-700';
                            @endphp
                            <img x-show="active === {{ $i }}"
                                 x-transition:enter="transition ease-out duration-700 {{ $delayClass }}"
                                 x-transition:enter-start="opacity-0 translate-y-12 scale-90"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-end="opacity-0"
                                 src="{{ asset($pack) }}" alt=""
                                 class="absolute bottom-[6%] drop-shadow-2xl"
                                 style="
                                    left: {{ $j * (58 / $count) }}%;
                                    height: {{ $scale * 78 }}%;
                                    z-index: {{ $j }};
                                 ">
                        @endforeach
                    </div>
                @elseif ($slide['pack'])
                    <img x-show="active === {{ $i }}"
                         x-transition:enter="transition ease-out duration-700 delay-200"
                         x-transition:enter-start="opacity-0 translate-y-10 scale-90"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-end="opacity-0"
                         src="{{ asset($slide['pack']) }}" alt=""
                         class="absolute bottom-[6%] hidden h-[80%] drop-shadow-2xl sm:block"
                         style="left: 38%;">
                @endif
            </div>
        @endforeach

        {{-- Persistent chrome: arrows --}}
        <button type="button" @click="stop(); prev()"
                class="absolute left-3 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/80 p-2.5 text-stone-800 shadow-md backdrop-blur transition hover:bg-white"
                aria-label="Previous slide">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button type="button" @click="stop(); next()"
                class="absolute right-3 top-1/2 z-20 -translate-y-1/2 rounded-full bg-white/80 p-2.5 text-stone-800 shadow-md backdrop-blur transition hover:bg-white"
                aria-label="Next slide">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </button>

        {{-- Persistent chrome: BRANDS tab --}}
        <a href="{{ route('ranges') }}"
           class="absolute right-0 top-8 z-20 hidden items-center gap-1 rounded-l-full bg-stone-950/85 py-2 pl-4 pr-5 text-xs font-semibold uppercase tracking-widest text-white shadow-md backdrop-blur transition hover:bg-stone-950 sm:flex">
            Brands <span aria-hidden="true">&rarr;</span>
        </a>
    </div>

    {{-- Persistent chrome: brand strip --}}
    <div class="relative z-20 border-t border-stone-200 bg-white py-3">
        <ul role="list" class="mx-auto flex max-w-7xl items-center justify-center gap-8 px-4 sm:gap-14">
            @foreach ($brands as $brand)
                <li>
                    <a href="{{ route('brand.show', $brand) }}" class="block opacity-70 transition hover:opacity-100">
                        <img src="{{ asset($brandMarks[$brand->slug] ?? '') }}" alt="{{ $brand->name }}"
                             class="h-7 w-auto object-contain sm:h-9">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
