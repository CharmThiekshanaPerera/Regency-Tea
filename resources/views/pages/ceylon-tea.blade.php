@extends('layouts.app')

@section('title', ($page->meta_title ?: 'The Story of Ceylon Tea').' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'From Edward Barnes and James Taylor to the golden Lion logo — the history behind the tea Regency Teas exports today.')
@section('og_image', \App\Support\Media::absoluteUrl($page->hero_image_path) ?: asset('images/ceylon-tea/hero.jpg'))

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[70vh] min-h-[480px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/ceylon-tea/hero.jpg') }}" alt="Ceylon tea terraces at dusk"
         class="absolute inset-0 h-full w-full object-cover animate-kenburns" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/90 via-stone-950/40 to-stone-950/10"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            150+ Years of Ceylon Tea
        </span>
        <h1 class="mt-6 max-w-2xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            A little island, world renowned for tea
        </h1>
        <p class="mt-5 max-w-xl text-lg text-stone-200">
            The history of Ceylon tea begins, ironically, with coffee — and tells a story of how
            a single verandah in the hill country came to define quality tea worldwide.
        </p>
    </div>
</section>

{{-- History --}}
<section class="overflow-hidden py-20" data-reveal>
    <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 lg:grid-cols-2">
        <div class="order-2 lg:order-1">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Where it began</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">From coffee crisis to tea empire</p>
            <p class="mt-5 text-stone-600">
                Sri Lanka — then Ceylon — had been under British rule since 1815. Holding the
                island was considered vital, but the military and infrastructure cost of doing so
                was prohibitive, and coffee was seen as the way to fund it.
            </p>
            <p class="mt-4 text-stone-600">
                Edward Barnes arrived in 1824. Around the same time, a tea plant brought from the
                highlands of China was planted — for curiosity, not commerce — in the Royal
                Botanical Gardens at Peradeniya. Further experimental plantings followed in 1839,
                brought from Assam and Calcutta via the East India Trading Company. Barnes had
                already laid the groundwork that let coffee planters access the resources they
                needed, developing the very regions tea would later make famous.
            </p>
        </div>
        <div class="order-1 lg:order-2 relative">
            <img src="{{ asset('images/ceylon-tea/history.jpg') }}"
                 alt="A historical photograph of a tea cart outside an early Ceylon tea factory"
                 class="aspect-[4/5] w-full rounded-2xl object-cover shadow-xl sepia-[.2]">
        </div>
    </div>
</section>

{{-- Quick facts --}}
<section class="bg-stone-950 py-20 text-white" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Quick facts</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">The moments that built the industry</p>
        </div>

        <ul role="list" class="mt-12 grid gap-6 sm:grid-cols-2">
            @foreach ([
                ['year' => '1867', 'icon' => 'icon-factory.png', 'text' => 'James Taylor, eager to experiment with tea, set up his own tea "factory" — probably the country\'s first — in the verandah of his bungalow at Loolecondera Estate.'],
                ['year' => '1883', 'icon' => 'icon-auction.png', 'text' => 'The first public Colombo Tea Auction was held at the premises of Somerville & Co. on 30 July, under the auspices of the Ceylon Chamber of Commerce.'],
                ['year' => '1870s–80s', 'icon' => 'icon-tasting.png', 'text' => 'Rapid expansion of the industry drew serious interest from large British companies, who took over many of the small original estates.'],
                ['year' => 'Today', 'icon' => 'icon-export.png', 'text' => 'Tea remains one of Sri Lanka\'s most important sources of foreign exchange, accounting for 65% of export-agriculture revenue and employing over 2 million people.'],
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
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">Terroir</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">150 years — and a thousand more</p>
            <p class="mt-5 text-stone-600">
                The alchemy of land, sun and rain across the paradise island once known as Ceylon
                presented ideal conditions for tea. Rather than one uniform crop, Ceylon tea added
                a new dimension — real variation in taste, quality, character and appearance,
                shaped largely by the terroir of each growing region.
            </p>
            <p class="mt-4 text-stone-600">
                Ceylon tea has charmed its way into royal courts and the cups of Hollywood's
                leading names. It has endured for over 150 years, and every batch we grade and
                export carries that same distinct character forward.
            </p>
        </div>
        <div class="relative">
            <img src="{{ asset('images/hero/plantation.jpg') }}" alt="Terraced Ceylon tea plantation"
                 class="aspect-[4/3] w-full rounded-2xl object-cover shadow-xl">
        </div>
    </div>
</section>

{{-- Symbol of quality --}}
<section class="border-y border-stone-200 bg-stone-50 py-20" data-reveal>
    <div class="mx-auto flex max-w-5xl flex-col items-center gap-8 px-4 text-center lg:flex-row lg:text-left">
        <img src="{{ asset('images/ceylon-tea/lion-mark.png') }}" alt="Ceylon Tea — Symbol of Quality lion logo"
             class="h-32 w-auto shrink-0 drop-shadow-md">
        <div>
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">The golden Lion</h2>
            <p class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">100% Pure Ceylon Tea, packed in Sri Lanka</p>
            <p class="mt-4 text-stone-600">
                The Lion logo signifies genuine, 100% Pure Ceylon Tea packed at origin — renowned
                as some of the finest tea in the world, and the first to be certified
                Ozone-friendly. Every Regency Teas export carries that same standard forward.
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
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">Taste the story</h2>
            <p class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Explore our Ceylon tea ranges</p>
            <p class="mt-4 text-stone-300">
                From single-region black teas to wellness infusions — every pack traces back to
                the same hill-country heritage.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('ranges') }}"
               class="rounded-full bg-white px-6 py-3 font-semibold text-emerald-900 transition hover:-translate-y-0.5 hover:bg-emerald-50">
                Browse ranges
            </a>
            <a href="{{ route('contact') }}"
               class="rounded-full border border-white/40 px-6 py-3 font-semibold transition hover:-translate-y-0.5 hover:bg-white/10">
                Talk to us
            </a>
        </div>
    </div>
</section>

@endsection
