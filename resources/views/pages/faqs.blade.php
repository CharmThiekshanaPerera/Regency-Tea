@extends('layouts.app')
@section('title', 'FAQs — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Answers to common questions about ordering, private label, certifications and export from Regency Teas.')

@php
$groups = [
    'Ordering & private label' => [
        'Do you offer private label / white label manufacturing?' =>
            'Yes — contract packing for private labels has been part of our business since the beginning. Our tasters build a blend to your brief, we pack it under your own branding in the format you need, and handle export documentation from Colombo. See the <a href="'.route('private-label').'" class="text-emerald-700 underline">Private Label</a> page for the full process.',
        'Do you supply wholesale and HORECA buyers, or only private label?' =>
            'Both. Alongside private label we supply wholesale and HORECA (hotel, restaurant &amp; catering) customers with our own Hyleys, Lakma, Truly Ceylon and Dr. Tea brands.',
        'What packaging formats can you produce?' =>
            'Tea bags (staple-free and pyramid), foil envelopes, loose leaf, tins, cartons and pouches — produced across our staple-free and pyramid lines at up to 2.75 million tea bags a day.',
        'Can you develop a custom blend for my market?' =>
            'Yes. Our tasters build blends to a target flavour profile, price point and market — classic black and green teas alongside specialty fruit and herbal blends from our own dedicated cold storage facility.',
    ],
    'Certifications & quality' => [
        'What certifications do you hold?' =>
            'We\'re certified through the Sri Lanka Tea Board and Bureau Veritas — GMP, ISO 22000 and FSSC 22000 — and licensed for Kosher, Halal and Organic-certified production.',
        'How is quality controlled?' =>
            'Every batch is tasted and graded in-house before it ships. Our team of professional tea tasters grades over 8,000 lots of tea every week at our own 90,000 sq&nbsp;ft facility.',
    ],
    'Export & logistics' => [
        'Which markets do you export to?' =>
            'We export to 25+ countries, with dedicated representatives in Canada, Russia and the USA — see the <a href="'.route('contact').'" class="text-emerald-700 underline">Contact</a> page for their details.',
        'Do you provide product catalogues?' =>
            'Yes — our full catalogue and a Hyleys-specific catalogue are both available as downloads on the <a href="'.route('catalogue').'" class="text-emerald-700 underline">Catalogue</a> page.',
    ],
    'Getting in touch' => [
        'How do I request a quote or samples?' =>
            'Send us your target market, volumes and packaging format through the <a href="'.route('contact').'" class="text-emerald-700 underline">Contact page</a> — we reply within 2 business days.',
        'Where are you located?' =>
            'Our head office and production facility are at No. 359, Biyagama Road, Kelaniya, Sri Lanka.',
    ],
];
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($groups)->flatMap(fn ($items) => $items)->map(fn ($answer, $question) => [
        '@type' => 'Question',
        'name' => $question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($answer)],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- Compact hero --}}
<section class="relative isolate flex h-[32vh] min-h-[240px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/sections/tea-pluckers.jpg') }}" alt="" aria-hidden="true"
         class="absolute inset-0 h-full w-full object-cover opacity-40">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/95 via-stone-950/70 to-stone-950/40"></div>

    <div class="relative z-10 mx-auto w-full max-w-4xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            FAQs
        </span>
        <h1 class="mt-4 text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            Frequently asked questions
        </h1>
    </div>
</section>

<div class="mx-auto max-w-3xl px-4 py-16">
    @foreach ($groups as $title => $items)
        <div class="mt-12 first:mt-0" data-reveal style="--reveal-delay: {{ $loop->index * 100 }}ms">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ $title }}</h2>
            <div class="mt-4"><x-accordion :items="$items" /></div>
        </div>
    @endforeach

    <div class="mt-16 rounded-2xl border border-stone-200 bg-stone-50 p-8 text-center" data-reveal>
        <p class="text-lg font-medium">Still have a question?</p>
        <p class="mt-2 text-sm text-stone-600">Our team replies within 2 business days.</p>
        <x-quote-button class="mt-5" />
    </div>
</div>
@endsection
