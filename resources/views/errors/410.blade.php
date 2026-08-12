@extends('layouts.app')
@section('title', 'Page removed — '.config('regency.company.name'))

@section('content')
<div class="mx-auto max-w-xl px-4 py-24 text-center">
    <p class="text-sm font-semibold uppercase tracking-widest text-stone-400">410</p>
    <h1 class="mt-3 text-4xl font-semibold tracking-tight">This page has been removed</h1>
    <p class="mt-4 text-stone-600">It was retired when we rebuilt the site and has no replacement.</p>
    <a href="{{ route('home') }}" class="mt-8 inline-block rounded-full bg-emerald-800 px-6 py-3 font-semibold text-white hover:bg-emerald-900">
        Back to home
    </a>
</div>
@endsection
