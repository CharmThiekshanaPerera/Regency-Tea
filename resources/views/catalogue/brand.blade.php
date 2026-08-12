@extends('layouts.app')
@section('title', ($brand->meta_title ?: $brand->name).' — '.config('regency.company.name'))
@section('meta_description', $brand->meta_description ?: $brand->name.' tea from Regency Teas — pure Ceylon tea, wholesale and private label.')

@section('content')
<x-breadcrumbs :items="['Brands' => null, $brand->name => null]" />

<div class="mx-auto max-w-7xl px-4 py-10">
    <header class="flex flex-wrap items-center gap-6">
        @if ($brand->logo_path)
            <x-media-image :path="$brand->logo_path" :alt="$brand->name" class="h-16 w-auto" />
        @endif
        <div>
            <h1 class="text-4xl font-semibold tracking-tight">{{ $brand->name }}</h1>
            <p class="mt-1 text-sm text-stone-500">{{ $products->total() }} products</p>
        </div>
    </header>

    @if ($brand->description)
        <div class="prose prose-stone mt-5 max-w-2xl">{!! $brand->description !!}</div>
    @endif

    @include('catalogue.partials.listing')
</div>
@endsection
