@props(['title' => '', 'url' => null])

@php
    $url   = $url ?? url()->current();
    $enc   = rawurlencode($url);
    $text  = rawurlencode($title);
    $links = [
        'Facebook'  => "https://www.facebook.com/sharer/sharer.php?u={$enc}",
        'X'         => "https://twitter.com/intent/tweet?url={$enc}&text={$text}",
        'LinkedIn'  => "https://www.linkedin.com/sharing/share-offsite/?url={$enc}",
        'Pinterest' => "https://pinterest.com/pin/create/button/?url={$enc}&description={$text}",
        'Email'     => "mailto:?subject={$text}&body={$enc}",
    ];
@endphp

<div class="flex flex-wrap items-center gap-3">
    <span class="text-sm text-stone-500">Share</span>
    <ul class="flex gap-2">
        @foreach ($links as $name => $href)
            <li>
                <a href="{{ $href }}"
                   @if ($name !== 'Email') target="_blank" rel="noopener noreferrer" @endif
                   title="Share on {{ $name }}"
                   class="flex h-9 w-9 items-center justify-center rounded-full border border-stone-300 text-xs font-semibold transition hover:border-emerald-600 hover:bg-emerald-50 hover:text-emerald-800">
                    <span aria-hidden="true">{{ $name === 'Email' ? '@' : Str::substr($name, 0, 1) }}</span>
                    <span class="sr-only">Share on {{ $name }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
