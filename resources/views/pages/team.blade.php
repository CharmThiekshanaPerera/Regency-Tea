@extends('layouts.app')
@section('title', 'Our Team — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'The tasters, graders and export specialists behind Regency Teas.')

@section('content')
<x-breadcrumbs :items="['Team' => null]" />

<div class="mx-auto max-w-3xl px-4 py-16 text-center">
    <h1 class="text-4xl font-semibold tracking-tight">{{ __('pages/team.hero.heading') }}</h1>
    <p class="mt-5 text-lg text-stone-600">
        {{ __('pages/team.hero.body', ['years' => now()->year - 1997]) }}
    </p>
    <div class="mt-8">
        <x-quote-button />
    </div>
</div>
@endsection
