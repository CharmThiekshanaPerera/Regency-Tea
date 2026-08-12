@props(['items' => []])

<nav aria-label="Breadcrumb" class="border-b border-stone-200 bg-stone-50">
    <ol class="mx-auto flex max-w-7xl flex-wrap items-center gap-2 px-4 py-3 text-sm text-stone-500">
        <li><a href="{{ route('home') }}" class="hover:text-emerald-700">Home</a></li>
        @foreach ($items as $label => $url)
            <li aria-hidden="true">/</li>
            <li>
                @if ($url && ! $loop->last)
                    <a href="{{ $url }}" class="hover:text-emerald-700">{{ $label }}</a>
                @else
                    <span class="text-stone-800" aria-current="page">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
