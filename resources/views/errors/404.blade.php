@extends('layouts.app')
@section('title', 'Page not found — '.config('regency.company.name'))

@section('content')
<div class="mx-auto max-w-xl px-4 py-24 text-center">
    <p class="text-sm font-semibold uppercase tracking-widest text-emerald-700">404</p>
    <h1 class="mt-3 text-4xl font-semibold tracking-tight">We couldn't find that page</h1>
    <p class="mt-4 text-stone-600">It may have moved during our site rebuild.</p>
    <div class="mt-8 flex justify-center gap-3">
        <a href="{{ route('home') }}" class="rounded-full bg-emerald-800 px-6 py-3 font-semibold text-white hover:bg-emerald-900">Home</a>
        <a href="{{ route('products') }}" class="rounded-full border border-stone-300 px-6 py-3 font-semibold hover:bg-stone-50">Browse teas</a>
    </div>
</div>
@endsection
