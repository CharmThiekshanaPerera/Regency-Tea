@extends('layouts.app')
@section('title', __('pages/catalogue.meta.title_prefix').config('regency.company.name'))
@section('meta_description', __('pages/catalogue.meta.description'))

@section('content')
<x-breadcrumbs :items="['Catalogue' => null]" />

<div class="mx-auto max-w-4xl px-4 py-14">
    <h1 class="text-4xl font-semibold tracking-tight">{{ __('pages/catalogue.hero.heading') }}</h1>
    <p class="mt-4 text-lg text-stone-600">
        {{ __('pages/catalogue.hero.subheading') }}
    </p>

    <ul role="list" class="mt-10 space-y-4">
        @foreach ($catalogues ?? [] as $item)
            <li class="flex items-center justify-between gap-6 rounded-xl border border-stone-200 p-6">
                <div>
                    <h2 class="text-lg font-medium">{{ $item['title'] }}</h2>
                    <p class="text-sm text-stone-500">{{ __('pages/catalogue.list.pdf_label') }} · {{ $item['size'] ?? '' }}</p>
                </div>
                <a href="{{ $item['url'] }}" target="_blank" rel="noopener"
                   class="shrink-0 rounded-full bg-emerald-800 px-6 py-2.5 font-semibold text-white hover:bg-emerald-900">
                    {{ __('pages/catalogue.list.download') }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
