<div class="mt-8 flex flex-col gap-10 lg:flex-row">
    <x-facets :filters="$filters" :brands="$brands ?? null" />

    <div class="flex-1">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm text-stone-500">
                {{ $products->total() }} {{ Str::plural('product', $products->total()) }}
            </p>
            <form method="get" class="flex items-center gap-2 text-sm">
                @foreach (request()->except(['sort', 'page']) as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
                <label for="sort" class="text-stone-500">Sort</label>
                <select id="sort" name="sort" onchange="this.form.submit()"
                        class="rounded-full border-stone-300 py-1.5 pl-3 pr-8 text-sm focus:border-emerald-600 focus:ring-emerald-600">
                    <option value="">Featured</option>
                    <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                    <option value="name" @selected(request('sort') === 'name')>Name A–Z</option>
                </select>
            </form>
        </div>

        <x-product-grid :products="$products" />
    </div>
</div>
