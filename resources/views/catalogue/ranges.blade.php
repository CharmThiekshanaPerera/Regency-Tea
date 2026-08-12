@extends('layouts.app')

@section('title', 'Product Ranges — '.config('regency.company.name'))
@section('meta_description', 'Explore the full range of Regency Teas Ceylon tea collections — black, green, herbal, wellness, gifting and bulk.')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-14">

    <header class="max-w-3xl">
        <h1 class="text-4xl font-semibold tracking-tight">Product Ranges</h1>
        <p class="mt-4 text-lg text-stone-600">
            Our complete Ceylon tea catalogue, organised by collection.
        </p>
    </header>

    <div class="mt-14 space-y-16">
        @foreach ($groups as $group)
            <section id="{{ $group->slug }}" class="scroll-mt-24">
                <h2 class="text-2xl font-semibold">{{ $group->name }}</h2>

                @if ($group->description)
                    <p class="mt-2 max-w-2xl text-stone-600">{{ $group->description }}</p>
                @endif

                <ul class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($group->categories as $category)
                        <li>
                            <a href="{{ route('range.show', $category) }}"
                               class="flex items-center justify-between gap-3 rounded-lg border border-stone-200 px-4 py-3 transition hover:border-emerald-600 hover:bg-emerald-50">
                                <span class="text-sm font-medium">{{ $category->name }}</span>
                                <span class="shrink-0 text-xs text-stone-500">{{ $category->products_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach
    </div>
</div>
@endsection
