@extends('layouts.app')
@section('title', 'Product Catalogue — '.config('regency.company.name'))
@section('meta_description', 'Download the Regency Teas product catalogue.')

@section('content')
<x-breadcrumbs :items="['Catalogue' => null]" />

<div class="mx-auto max-w-4xl px-4 py-14">
    <h1 class="text-4xl font-semibold tracking-tight">Product catalogue</h1>
    <p class="mt-4 text-lg text-stone-600">
        Our full range, with item codes and pack specifications.
    </p>

    <ul role="list" class="mt-10 space-y-4">
        @foreach ($catalogues ?? [] as $item)
            <li class="flex items-center justify-between gap-6 rounded-xl border border-stone-200 p-6">
                <div>
                    <h2 class="text-lg font-medium">{{ $item['title'] }}</h2>
                    <p class="text-sm text-stone-500">PDF · {{ $item['size'] ?? '' }}</p>
                </div>
                <a href="{{ $item['url'] }}" target="_blank" rel="noopener"
                   class="shrink-0 rounded-full bg-emerald-800 px-6 py-2.5 font-semibold text-white hover:bg-emerald-900">
                    Download
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
