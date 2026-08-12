@props([
    'path' => null,
    'alt' => '',
    'class' => '',
    'sizes' => '(min-width: 1024px) 25vw, 50vw',
    'eager' => false,
])

@php $src = \App\Support\Media::url($path); @endphp

@if ($src)
    <img src="{{ $src }}"
         alt="{{ $alt }}"
         sizes="{{ $sizes }}"
         loading="{{ $eager ? 'eager' : 'lazy' }}"
         decoding="async"
         onerror="this.closest('[data-img-wrap]')?.classList.add('img-missing'); this.remove();"
         @class(['object-contain', $class])>
@else
    <div @class(['flex items-center justify-center bg-stone-100 text-xs text-stone-400', $class])>
        No image
    </div>
@endif
