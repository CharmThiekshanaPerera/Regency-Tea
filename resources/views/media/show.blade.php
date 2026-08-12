@extends('layouts.app')
@section('title', ($post->meta_title ?: $post->title).' — '.config('regency.company.name'))
@section('meta_description', $post->meta_description ?: Str::limit(strip_tags($post->excerpt ?? $post->body), 155))
@section('og_type', 'article')
@section('og_image', \App\Support\Media::absoluteUrl($post->featured_image_path) ?: asset('images/og-default.jpg'))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $post->title,
    'datePublished' => $post->published_at?->toIso8601String(),
    'image' => \App\Support\Media::absoluteUrl($post->featured_image_path),
    'publisher' => ['@type' => 'Organization', 'name' => config('regency.company.name')],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<x-breadcrumbs :items="['Media Centre' => route('media'), $post->title => null]" />

<article class="mx-auto max-w-3xl px-4 py-12">
    <header>
        <div class="flex flex-wrap items-center gap-3 text-xs uppercase tracking-wide text-stone-500">
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('j F Y') }}</time>
            @foreach ($post->categories as $category)
                <a href="{{ route('media.category', $category) }}" class="text-emerald-700 hover:underline">{{ $category->name }}</a>
            @endforeach
        </div>
        <h1 class="mt-3 text-4xl font-semibold tracking-tight">{{ $post->title }}</h1>
    </header>

    @if ($post->featured_image_path)
        <x-media-image :path="$post->featured_image_path" :alt="$post->title" :eager="true"
                       class="mt-8 aspect-[16/9] w-full rounded-xl !object-cover" />
    @endif

    <div class="prose prose-stone mt-10 max-w-none">{!! $post->body !!}</div>

    <div class="mt-10 border-t border-stone-200 pt-6">
        <x-social-share :title="$post->title" />
    </div>
</article>

@if ($related->isNotEmpty())
    <section class="mx-auto max-w-7xl border-t border-stone-200 px-4 py-12">
        <h2 class="text-2xl font-semibold">More from the Media Centre</h2>
        <ul role="list" class="mt-6 grid gap-8 sm:grid-cols-3">
            @foreach ($related as $item)
                <li>
                    <a href="{{ route('media.show', $item) }}" class="group block">
                        <x-media-image :path="$item->featured_image_path" :alt="$item->title"
                                       class="aspect-[16/10] w-full rounded-lg !object-cover" />
                        <h3 class="mt-3 font-medium group-hover:text-emerald-700">{{ $item->title }}</h3>
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
@endif
@endsection
