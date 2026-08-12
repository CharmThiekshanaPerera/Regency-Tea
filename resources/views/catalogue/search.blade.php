@extends('layouts.app')
@section('title', 'Search — '.config('regency.company.name'))

@section('content')
<x-breadcrumbs :items="['Search' => null]" />
<div class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-3xl font-semibold">Search</h1>

    <form method="get" role="search" class="mt-6 flex max-w-xl gap-3">
        <label for="q" class="sr-only">Search products</label>
        <input id="q" type="search" name="q" value="{{ $term }}" placeholder="Search by name or item code…"
               class="flex-1 rounded-full border-stone-300 px-5 py-3 focus:border-emerald-600 focus:ring-emerald-600">
        <button class="rounded-full bg-emerald-800 px-6 py-3 font-semibold text-white hover:bg-emerald-900">Search</button>
    </form>

    @if ($term !== '')
        <p class="mt-6 text-sm text-stone-500">
            {{ $products->total() }} {{ Str::plural('result', $products->total()) }} for “{{ $term }}”
        </p>
        <div class="mt-6">
            <x-product-grid :products="$products" empty="Nothing matched that search. Try a broader term." />
        </div>
    @endif
</div>
@endsection
