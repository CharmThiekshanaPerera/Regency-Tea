@extends('layouts.app')
@section('title', 'FAQs — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Answers to common questions about ordering, private label, certifications and export from Regency Teas.')

@php
$groups = [
    __('pages/faqs.groups.ordering.title') => [
        __('pages/faqs.groups.ordering.items.private_label.question') =>
            __('pages/faqs.groups.ordering.items.private_label.answer', ['url' => route('private-label')]),
        __('pages/faqs.groups.ordering.items.wholesale_horeca.question') =>
            __('pages/faqs.groups.ordering.items.wholesale_horeca.answer'),
        __('pages/faqs.groups.ordering.items.packaging_formats.question') =>
            __('pages/faqs.groups.ordering.items.packaging_formats.answer'),
        __('pages/faqs.groups.ordering.items.custom_blend.question') =>
            __('pages/faqs.groups.ordering.items.custom_blend.answer'),
    ],
    __('pages/faqs.groups.certifications.title') => [
        __('pages/faqs.groups.certifications.items.certifications_held.question') =>
            __('pages/faqs.groups.certifications.items.certifications_held.answer'),
        __('pages/faqs.groups.certifications.items.quality_control.question') =>
            __('pages/faqs.groups.certifications.items.quality_control.answer'),
    ],
    __('pages/faqs.groups.export.title') => [
        __('pages/faqs.groups.export.items.markets.question') =>
            __('pages/faqs.groups.export.items.markets.answer', ['url' => route('contact')]),
        __('pages/faqs.groups.export.items.catalogues.question') =>
            __('pages/faqs.groups.export.items.catalogues.answer', ['url' => route('catalogue')]),
    ],
    __('pages/faqs.groups.contact.title') => [
        __('pages/faqs.groups.contact.items.quote_samples.question') =>
            __('pages/faqs.groups.contact.items.quote_samples.answer', ['url' => route('contact')]),
        __('pages/faqs.groups.contact.items.location.question') =>
            __('pages/faqs.groups.contact.items.location.answer'),
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
            {{ __('pages/faqs.hero.badge') }}
        </span>
        <h1 class="mt-4 text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            {{ __('pages/faqs.hero.heading') }}
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
        <p class="text-lg font-medium">{{ __('pages/faqs.cta.question') }}</p>
        <p class="mt-2 text-sm text-stone-600">{{ __('pages/faqs.cta.reply_note') }}</p>
        <x-quote-button class="mt-5" />
    </div>
</div>
@endsection
