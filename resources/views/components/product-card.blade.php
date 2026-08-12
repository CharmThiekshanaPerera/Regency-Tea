@props(['product'])

@php
    $variantCount = $product->relationLoaded('variants') ? $product->variants->count() : 0;
    $hover        = collect($product->gallery ?? [])->first();
@endphp

<article class="group flex flex-col overflow-hidden rounded-lg border border-stone-200 bg-white transition hover:shadow-lg">
    <a href="{{ route('product.show', $product) }}"
       class="relative block aspect-square overflow-hidden bg-stone-50">
        <x-media-image :path="$product->primary_image_path" :alt="$product->title"
                       class="h-full w-full p-4 transition duration-300 group-hover:scale-105" />

        {{-- second image fades in on hover, matching the legacy theme behaviour --}}
        @if ($hover)
            <x-media-image :path="$hover" alt=""
                           class="absolute inset-0 h-full w-full p-4 opacity-0 transition duration-300 group-hover:opacity-100" />
        @endif
    </a>

    <div class="flex flex-1 flex-col gap-2 p-4">
        @if ($product->brand)
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">{{ $product->brand->name }}</p>
        @endif

        <h3 class="text-sm font-medium leading-snug">
            <a href="{{ route('product.show', $product) }}" class="hover:text-emerald-700">{{ $product->title }}</a>
        </h3>

        @if ($variantCount > 1)
            <p class="text-xs text-stone-500">{{ $variantCount }} pack sizes</p>
        @endif

        <div class="mt-auto pt-2">
            @if ($product->is_purchasable)
                <span class="text-sm font-semibold">{{ $product->variants->first()->formatted_price }}</span>
            @else
                <a href="{{ route('contact', ['product' => $product->slug]) }}"
                   class="text-sm font-semibold text-emerald-800 hover:underline">Request a quote</a>
            @endif
        </div>
    </div>
</article>
