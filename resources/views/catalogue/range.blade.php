@extends('layouts.app')
@section('title', ($category->meta_title ?: $category->name).' — '.config('regency.company.name'))
@section('meta_description', $category->meta_description ?: 'Explore our '.$category->name.' range of pure Ceylon tea.')

@section('content')
<x-breadcrumbs :items="array_filter([
    'Product Ranges' => route('ranges'),
    $category->group?->name => $category->group ? route('ranges').'#'.$category->group->slug : null,
    $category->name => null,
])" />

<div class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-4xl font-semibold tracking-tight">{{ $category->name }}</h1>
    @if ($category->description)
        <div class="prose prose-stone mt-3 max-w-2xl">{!! $category->description !!}</div>
    @endif
    @include('catalogue.partials.listing')
</div>
@endsection
