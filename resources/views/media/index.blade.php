@extends('layouts.app')
@section('title', (($current ?? null) ? $current->name : 'Media Centre').' — '.config('regency.company.name'))
@section('meta_description', 'News, media coverage and CSR initiatives from Regency Teas.')

@section('content')
<x-breadcrumbs :items="array_filter(['Media Centre' => route('media'), (($current ?? null)?->name ?? '') => null])" />

<div class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-4xl font-semibold tracking-tight">{{ ($current ?? null)?->name ?? 'Media Centre' }}</h1>

    <nav class="mt-6 flex flex-wrap gap-2" aria-label="Categories">
        <a href="{{ route('media') }}"
           @class(['rounded-full border px-4 py-1.5 text-sm', 'border-emerald-700 bg-emerald-50 text-emerald-800' => ! isset($current), 'border-stone-300 hover:bg-stone-50' => isset($current)])>
            All
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('media.category', $category) }}"
               @class(['rounded-full border px-4 py-1.5 text-sm', 'border-emerald-700 bg-emerald-50 text-emerald-800' => ($current ?? null)?->id === $category->id, 'border-stone-300 hover:bg-stone-50' => ($current ?? null)?->id !== $category->id])>
                {{ $category->name }} <span class="text-stone-400">{{ $category->posts_count ?? '' }}</span>
            </a>
        @endforeach
    </nav>

    @if ($posts->count())
        <ul role="list" class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <li>
                    <article class="group flex h-full flex-col overflow-hidden rounded-xl border border-stone-200">
                        <a href="{{ route('media.show', $post) }}" class="block overflow-hidden bg-stone-50">
                            <x-media-image :path="$post->featured_image_path" :alt="$post->title"
                                           class="aspect-[16/10] w-full !object-cover transition duration-300 group-hover:scale-105" />
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            <time datetime="{{ $post->published_at?->toDateString() }}" class="text-xs uppercase tracking-wide text-stone-500">
                                {{ $post->published_at?->format('j F Y') }}
                            </time>
                            <h2 class="mt-2 text-lg font-medium leading-snug">
                                <a href="{{ route('media.show', $post) }}" class="hover:text-emerald-700">{{ $post->title }}</a>
                            </h2>
                            @if ($post->excerpt)
                                <p class="mt-2 line-clamp-3 text-sm text-stone-600">{{ Str::limit(strip_tags($post->excerpt), 140) }}</p>
                            @endif
                            <a href="{{ route('media.show', $post) }}" class="mt-auto pt-4 text-sm font-semibold text-emerald-800 hover:underline">
                                Read more
                            </a>
                        </div>
                    </article>
                </li>
            @endforeach
        </ul>
        <div class="mt-12">{{ $posts->links() }}</div>
    @else
        <p class="mt-10 text-stone-500">No articles yet.</p>
    @endif
</div>
@endsection
