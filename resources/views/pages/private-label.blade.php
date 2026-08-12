@extends('layouts.app')
@section('title', 'Private Label — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Private-label Ceylon tea manufacturing — blending, packing, branding and export documentation from Regency Teas.')

@section('content')
<x-breadcrumbs :items="['Private Label' => null]" />

<div class="mx-auto max-w-5xl px-4 py-14">
    <h1 class="text-4xl font-semibold tracking-tight">Private label</h1>
    <p class="mt-4 max-w-2xl text-lg text-stone-600">
        {{ $page->excerpt ?: 'We produce private-label Ceylon tea for retailers, distributors and foodservice operators worldwide.' }}
    </p>

    <div class="prose prose-stone mt-10 max-w-none">{!! $page->body !!}</div>

    @php
        $steps = [
            'Blend development' => 'Our tea tasters build a blend to your target profile, price point and market.',
            'Packaging'         => 'Tea bags, foil envelopes, pyramid bags, loose leaf, tins, cartons and pouches.',
            'Branding'          => 'Your artwork, our production. Full design support if you need it.',
            'Export'            => 'Documentation, certification and logistics handled from Colombo.',
        ];
    @endphp

    <ul role="list" class="mt-14 grid gap-6 sm:grid-cols-2">
        @foreach ($steps as $title => $body)
            <li class="rounded-xl border border-stone-200 p-6">
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">
                    Step {{ $loop->iteration }}
                </p>
                <h2 class="mt-2 text-lg font-medium">{{ $title }}</h2>
                <p class="mt-2 text-sm text-stone-600">{{ $body }}</p>
            </li>
        @endforeach
    </ul>

    <div class="mt-14 rounded-xl bg-emerald-900 p-10 text-white">
        <h2 class="text-2xl font-semibold">Start a private-label project</h2>
        <p class="mt-2 max-w-xl text-emerald-100">
            Tell us your target market, volumes and packaging format, and we will send a proposal.
        </p>
        <a href="{{ route('contact') }}"
           class="mt-6 inline-block rounded-full bg-white px-7 py-3 font-semibold text-emerald-900 hover:bg-emerald-50">
            Talk to us
        </a>
    </div>
</div>
@endsection
