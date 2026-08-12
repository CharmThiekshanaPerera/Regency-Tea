@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' — '.config('regency.company.name'))
@section('meta_description', $page->meta_description)
@section('og_image', \App\Support\Media::absoluteUrl($page->og_image_path) ?: asset('images/og-default.jpg'))

@section('content')
<x-breadcrumbs :items="[$page->title => null]" />

@if ($page->hero_image_path)
    <div class="relative">
        <x-media-image :path="$page->hero_image_path" :alt="$page->title" :eager="true"
                       class="h-64 w-full !object-cover md:h-80" />
        <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/60">
            <div class="mx-auto w-full max-w-4xl px-4 pb-8">
                <h1 class="text-4xl font-semibold tracking-tight text-white">{{ $page->title }}</h1>
            </div>
        </div>
    </div>
@endif

<article class="mx-auto max-w-3xl px-4 py-12">
    @unless ($page->hero_image_path)
        <h1 class="text-4xl font-semibold tracking-tight">{{ $page->title }}</h1>
    @endunless

    @if ($page->excerpt)
        <p class="mt-4 text-lg text-stone-600">{{ $page->excerpt }}</p>
    @endif

    <div class="prose prose-stone mt-8 max-w-none">
        {!! $page->body !!}
    </div>
</article>

<section class="border-t border-stone-200 bg-stone-50 py-12">
    <div class="mx-auto flex max-w-4xl flex-wrap items-center justify-between gap-6 px-4">
        <p class="text-lg font-medium">Interested in working with us?</p>
        <x-quote-button />
    </div>
</section>
@endsection
