@props(['slides'])

@if ($slides->isNotEmpty())
<section class="relative overflow-hidden bg-stone-900" data-hero aria-roledescription="carousel" aria-label="Featured">
    <div class="flex snap-x snap-mandatory overflow-x-auto scroll-smooth" data-hero-track>
        @foreach ($slides as $slide)
            <div class="relative w-full shrink-0 snap-center" role="group"
                 aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $slides->count() }}">
                <x-media-image :path="$slide->image_path" :alt="$slide->heading ?? ''"
                               :eager="$loop->first"
                               class="h-[420px] w-full !object-cover md:h-[560px]" />

                <div class="absolute inset-0 flex items-center bg-gradient-to-r from-black/60 to-transparent">
                    <div class="mx-auto w-full max-w-7xl px-6">
                        <div class="max-w-xl text-white">
                            @if ($slide->subheading)
                                <p class="text-sm font-semibold uppercase tracking-widest text-emerald-300">
                                    {{ $slide->subheading }}
                                </p>
                            @endif
                            @if ($slide->heading)
                                <h2 class="mt-3 text-4xl font-semibold md:text-5xl">{{ $slide->heading }}</h2>
                            @endif
                            @if ($slide->body)
                                <p class="mt-4 text-lg text-stone-200">{{ $slide->body }}</p>
                            @endif
                            @if ($slide->cta_url)
                                <a href="{{ $slide->cta_url }}"
                                   class="mt-7 inline-block rounded-full bg-emerald-600 px-7 py-3 font-semibold hover:bg-emerald-500">
                                    {{ $slide->cta_label ?? 'Discover' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($slides->count() > 1)
        <div class="absolute bottom-5 left-1/2 flex -translate-x-1/2 gap-2">
            @foreach ($slides as $slide)
                <button type="button" data-hero-dot="{{ $loop->index }}"
                        class="h-2 w-8 rounded-full bg-white/50 transition hover:bg-white"
                        aria-label="Go to slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
    @endif
</section>
@endif
