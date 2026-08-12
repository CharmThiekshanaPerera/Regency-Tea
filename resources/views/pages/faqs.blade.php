@extends('layouts.app')
@section('title', 'FAQs — '.config('regency.company.name'))
@section('meta_description', $page->meta_description ?: 'Frequently asked questions about Regency Teas, our products, ordering and private-label service.')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($page->blocks ?? [])->map(fn ($b) => [
        '@type' => 'Question',
        'name' => $b['question'] ?? '',
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($b['answer'] ?? '')],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<x-breadcrumbs :items="['FAQs' => null]" />

<div class="mx-auto max-w-3xl px-4 py-12">
    <h1 class="text-4xl font-semibold tracking-tight">Frequently asked questions</h1>

    @php
        $items = collect($page->blocks ?? [])
            ->mapWithKeys(fn ($b) => [($b['question'] ?? '') => ($b['answer'] ?? '')])
            ->filter(fn ($v, $k) => $k !== '')
            ->all();
    @endphp

    @if ($items)
        <div class="mt-10"><x-accordion :items="$items" /></div>
    @else
        <div class="prose prose-stone mt-8 max-w-none">{!! $page->body !!}</div>
    @endif
</div>
@endsection
