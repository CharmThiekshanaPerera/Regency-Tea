@props(['previous' => null, 'next' => null])

@if ($previous || $next)
<nav class="flex items-center justify-between gap-4 border-t border-stone-200 pt-6"
     aria-label="Product navigation">
    <div class="flex-1">
        @if ($previous)
            <a href="{{ route('product.show', $previous) }}"
               class="group inline-flex items-center gap-3 text-sm">
                <span aria-hidden="true" class="text-stone-400 group-hover:text-emerald-700">&larr;</span>
                <x-media-image :path="$previous->primary_image_path" :alt="$previous->title"
                               class="h-12 w-12 rounded border border-stone-200" />
                <span>
                    <span class="block text-xs uppercase tracking-wide text-stone-400">Previous</span>
                    <span class="group-hover:text-emerald-700">{{ Str::limit($previous->title, 34) }}</span>
                </span>
            </a>
        @endif
    </div>

    <div class="flex-1 text-right">
        @if ($next)
            <a href="{{ route('product.show', $next) }}"
               class="group inline-flex items-center gap-3 text-sm">
                <span>
                    <span class="block text-xs uppercase tracking-wide text-stone-400">Next</span>
                    <span class="group-hover:text-emerald-700">{{ Str::limit($next->title, 34) }}</span>
                </span>
                <x-media-image :path="$next->primary_image_path" :alt="$next->title"
                               class="h-12 w-12 rounded border border-stone-200" />
                <span aria-hidden="true" class="text-stone-400 group-hover:text-emerald-700">&rarr;</span>
            </a>
        @endif
    </div>
</nav>
@endif
