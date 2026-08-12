@props(['products', 'empty' => 'No products match your selection.'])

@if ($products->count())
    <ul role="list" class="grid grid-cols-2 gap-5 sm:grid-cols-3 xl:grid-cols-4">
        @foreach ($products as $product)
            <li><x-product-card :product="$product" /></li>
        @endforeach
    </ul>

    @if (method_exists($products, 'links'))
        <div class="mt-10">{{ $products->links() }}</div>
    @endif
@else
    <p class="rounded-lg border border-dashed border-stone-300 px-6 py-16 text-center text-stone-500">
        {{ $empty }}
    </p>
@endif
