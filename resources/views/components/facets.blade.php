@props(['filters', 'brands' => null])

<aside class="lg:w-64 lg:shrink-0" aria-label="Filters">
    <form method="get" class="space-y-6">
        @foreach (request()->except(array_merge($filters->pluck('slug')->all(), ['brand', 'page'])) as $k => $v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach

        @if ($brands && $brands->isNotEmpty())
            <fieldset>
                <legend class="text-sm font-semibold uppercase tracking-wide text-stone-500">Brand</legend>
                <div class="mt-3 space-y-2">
                    @foreach ($brands as $brand)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="brand" value="{{ $brand->slug }}"
                                   @checked(request('brand') === $brand->slug)
                                   class="text-emerald-700 focus:ring-emerald-600">
                            {{ $brand->name }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endif

        @foreach ($filters as $attribute)
            @continue($attribute->values->isEmpty())
            <fieldset>
                <legend class="text-sm font-semibold uppercase tracking-wide text-stone-500">
                    {{ $attribute->name }}
                </legend>
                <div class="mt-3 max-h-56 space-y-2 overflow-y-auto pr-1">
                    @foreach ($attribute->values as $value)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="{{ $attribute->slug }}" value="{{ $value->slug }}"
                                   @checked(request($attribute->slug) === $value->slug)
                                   class="text-emerald-700 focus:ring-emerald-600">
                            {{ $value->value }}
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endforeach

        <div class="flex gap-2">
            <button type="submit"
                    class="flex-1 rounded-full bg-emerald-800 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-900">
                Apply
            </button>
            <a href="{{ url()->current() }}"
               class="rounded-full border border-stone-300 px-4 py-2 text-sm hover:bg-stone-50">Clear</a>
        </div>
    </form>
</aside>
