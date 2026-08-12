@extends('layouts.app')
@section('title', 'Our Team — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Meet the team behind Regency Teas.')

@section('content')
<x-breadcrumbs :items="['Team' => null]" />

<div class="mx-auto max-w-7xl px-4 py-12">
    <h1 class="text-4xl font-semibold tracking-tight">Our team</h1>

    @if ($page->excerpt)
        <p class="mt-4 max-w-2xl text-lg text-stone-600">{{ $page->excerpt }}</p>
    @endif

    @php $members = collect($page->blocks ?? []); @endphp

    @if ($members->isNotEmpty())
        <ul role="list" class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($members as $member)
                <li class="text-center">
                    <x-media-image :path="$member['image'] ?? null" :alt="$member['name'] ?? ''"
                                   class="mx-auto aspect-square w-40 rounded-full !object-cover" />
                    <h2 class="mt-4 font-medium">{{ $member['name'] ?? '' }}</h2>
                    <p class="text-sm text-stone-500">{{ $member['role'] ?? '' }}</p>
                </li>
            @endforeach
        </ul>
    @else
        <div class="prose prose-stone mt-8 max-w-none">{!! $page->body !!}</div>
    @endif
</div>
@endsection
