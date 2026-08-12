@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->title.' — '.($product->brand?->name ?? 'Regency Teas')))
@section('meta_description', $product->meta_description ?: Str::limit(strip_tags($product->short_description), 155))
@section('og_type', 'product')
@section('og_image', \App\Support\Media::absoluteUrl($product->primary_image_path) ?: asset('images/og-default.jpg'))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $product->title,
    'description' => strip_tags($product->short_description ?? ''),
    'sku' => $product->variants->first()?->sku,
    'brand' => $product->brand ? ['@type' => 'Brand', 'name' => $product->brand->name] : null,
    'image' => \App\Support\Media::absoluteUrl($product->primary_image_path),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<x-breadcrumbs :items="array_filter([
    'Products' => route('products'),
    $product->brand?->name => $product->brand ? route('brand.show', $product->brand) : null,
    $product->title => null,
])" />

<div class="mx-auto max-w-7xl px-4 py-10">
    <div class="grid gap-10 lg:grid-cols-2">

        {{-- Gallery --}}
        <div x-data="{ active: '{{ \App\Support\Media::url($product->primary_image_path) }}' }">
            <div class="overflow-hidden rounded-xl border border-stone-200 bg-white">
                <template x-if="active">
                    <img :src="active" alt="{{ $product->title }}"
                         class="aspect-square w-full object-contain p-8">
                </template>
                @if (! $product->primary_image_path)
                    <div class="flex aspect-square items-center justify-center text-stone-400">No image</div>
                @endif
            </div>

            @php $thumbs = collect([$product->primary_image_path])->merge($product->gallery ?? [])->filter()->unique(); @endphp
            @if ($thumbs->count() > 1)
                <ul class="mt-4 grid grid-cols-5 gap-3">
                    @foreach ($thumbs as $thumb)
                        <li>
                            <button type="button" @click="active = '{{ $thumb }}'"
                                    class="block w-full overflow-hidden rounded-lg border border-stone-200 p-1 hover:border-emerald-600"
                                    :class="active === '{{ \App\Support\Media::url($thumb) }}' ? 'border-emerald-600 ring-1 ring-emerald-600' : ''"
                                    aria-label="View image {{ $loop->iteration }}">
                                <x-media-image :path="$thumb" :alt="$product->title" class="aspect-square w-full" />
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Summary --}}
        <div class="lg:sticky lg:top-24 lg:self-start">
            @if ($product->brand)
                <a href="{{ route('brand.show', $product->brand) }}"
                   class="text-sm font-semibold uppercase tracking-widest text-emerald-700 hover:underline">
                    {{ $product->brand->name }}
                </a>
            @endif

            <h1 class="mt-2 text-3xl font-semibold tracking-tight">{{ $product->title }}</h1>

            @if ($product->short_description)
                <div class="prose prose-stone mt-4 max-w-none">{!! $product->short_description !!}</div>
            @endif

            {{-- Pack size selector (legacy pack sizes became variants) --}}
            @if ($product->variants->count() > 1)
                <div class="mt-7">
                    <p class="text-sm font-semibold uppercase tracking-wide text-stone-500">
                        Available pack sizes ({{ $product->variants->count() }})
                    </p>
                    <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                        @foreach ($product->variants as $variant)
                            <li class="flex items-center justify-between gap-3 rounded-lg border border-stone-200 px-3 py-2 text-sm">
                                <span>{{ $variant->pack_size ?? 'Standard' }}</span>
                                @if ($variant->sku)
                                    {{-- legacy theme relabelled SKU to "Item Code" --}}
                                    <span class="shrink-0 text-xs text-stone-500">Item Code: {{ $variant->sku }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif ($first = $product->variants->first())
                <dl class="mt-7 space-y-1 text-sm">
                    @if ($first->pack_size)
                        <div class="flex gap-2"><dt class="text-stone-500">Pack size:</dt><dd>{{ $first->pack_size }}</dd></div>
                    @endif
                    @if ($first->sku)
                        <div class="flex gap-2"><dt class="text-stone-500">Item Code:</dt><dd>{{ $first->sku }}</dd></div>
                    @endif
                </dl>
            @endif

            <div class="mt-8">
                <x-quote-button :product="$product" class="w-full sm:w-auto" />
                <p class="mt-3 text-sm text-stone-500">
                    Wholesale and private-label enquiries welcome. We respond within two business days.
                </p>

                <div class="mt-6">
                    <x-social-share :title="$product->title" />
                </div>
            </div>

            {{-- Meta --}}
            <dl class="mt-8 space-y-2 border-t border-stone-200 pt-6 text-sm">
                @if ($product->categories->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        <dt class="text-stone-500">Categories:</dt>
                        <dd class="flex flex-wrap gap-2">
                            @foreach ($product->categories as $category)
                                <a href="{{ route('range.show', $category) }}"
                                   class="text-emerald-700 hover:underline">{{ $category->name }}</a>
                            @endforeach
                        </dd>
                    </div>
                @endif
                @foreach ($product->attributeValues->groupBy(fn ($v) => $v->attribute->name) as $label => $values)
                    <div class="flex flex-wrap gap-2">
                        <dt class="text-stone-500">{{ $label }}:</dt>
                        <dd>{{ $values->pluck('value')->join(', ') }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>

    {{-- Tabs --}}
    @php
        $tabs = array_filter([
            'Description'       => $product->description,
            'Technical specs'   => $product->technical_specs ?: $product->variants->pluck('technical_specs')->filter()->first(),
            'Specifications'    => $product->custom_attributes ? true : null,
        ]);
    @endphp

    @if ($tabs)
        <div class="mt-16" x-data="{ tab: '{{ array_key_first($tabs) }}' }">
            <div class="border-b border-stone-200">
                <nav class="-mb-px flex flex-wrap gap-6" role="tablist">
                    @foreach (array_keys($tabs) as $name)
                        <button type="button" role="tab" @click="tab = '{{ $name }}'"
                                :aria-selected="tab === '{{ $name }}'"
                                class="border-b-2 pb-3 text-sm font-medium transition"
                                :class="tab === '{{ $name }}' ? 'border-emerald-700 text-emerald-800' : 'border-transparent text-stone-500 hover:text-stone-800'">
                            {{ $name }}
                        </button>
                    @endforeach
                </nav>
            </div>

            <div class="py-8">
                @if (! empty($tabs['Description']))
                    <div x-show="tab === 'Description'" class="prose prose-stone max-w-none">
                        {!! $product->description !!}
                    </div>
                @endif

                @if (! empty($tabs['Technical specs']))
                    <div x-show="tab === 'Technical specs'" x-cloak class="prose prose-stone max-w-none">
                        <p>{{ $tabs['Technical specs'] }}</p>
                    </div>
                @endif

                @if ($product->custom_attributes)
                    <div x-show="tab === 'Specifications'" x-cloak>
                        <table class="w-full max-w-2xl text-sm">
                            <tbody class="divide-y divide-stone-200">
                            @foreach ($product->custom_attributes as $name => $values)
                                <tr>
                                    <th scope="row" class="py-3 pr-6 text-left font-medium text-stone-500">{{ $name }}</th>
                                    <td class="py-3">{{ is_array($values) ? implode(', ', $values) : $values }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="mt-14">
        <x-product-nav :previous="$previous" :next="$next" />
    </div>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="mt-16">
            <h2 class="text-2xl font-semibold">More from {{ $product->brand?->name ?? 'our range' }}</h2>
            <div class="mt-6"><x-product-grid :products="$related" /></div>
        </section>
    @endif
</div>
@endsection
