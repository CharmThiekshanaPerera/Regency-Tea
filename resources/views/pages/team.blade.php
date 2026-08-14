@extends('layouts.app')
@section('title', 'Our Team — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'The tasters, graders and export specialists behind Regency Teas.')

@section('content')
<x-breadcrumbs :items="['Team' => null]" />

<div class="mx-auto max-w-3xl px-4 py-16 text-center">
    <h1 class="text-4xl font-semibold tracking-tight">Our team</h1>
    <p class="mt-5 text-lg text-stone-600">
        For over {{ now()->year - 1997 }} years, Regency Teas has been built by tasters, graders
        and export specialists who turned a plantation-management background into a pure Ceylon
        tea export house. Our tasters grade over 8,000 lots of tea every week, and our export team
        handles documentation and logistics for buyers in 25+ countries — every batch tasted
        before it ships.
    </p>
    <div class="mt-8">
        <x-quote-button />
    </div>
</div>
@endsection
