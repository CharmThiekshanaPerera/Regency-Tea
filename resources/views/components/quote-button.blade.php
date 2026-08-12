@props(['product' => null, 'variant' => null, 'class' => ''])

<a href="{{ route('contact', array_filter([
        'product' => $product?->slug,
        'variant' => $variant?->id,
        'code'    => $variant?->sku,
   ])) }}"
   @class(['inline-flex items-center justify-center gap-2 rounded-full bg-emerald-800 px-6 py-3 font-semibold text-white transition hover:bg-emerald-900', $class])>
    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M8 10h8M8 14h5M21 12a9 9 0 1 1-3.5-7.1L21 4v8Z"/>
    </svg>
    Request a quote
</a>
