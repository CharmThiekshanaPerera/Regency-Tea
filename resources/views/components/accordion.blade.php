@props(['items' => []])

<div class="divide-y divide-stone-200 border-y border-stone-200">
    @foreach ($items as $question => $answer)
        <details class="group py-4" @if($loop->first) open @endif>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-medium">
                <span>{{ $question }}</span>
                <svg class="h-5 w-5 shrink-0 transition group-open:rotate-45" fill="none"
                     stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                </svg>
            </summary>
            <div class="prose prose-stone mt-3 max-w-none text-sm">{!! $answer !!}</div>
        </details>
    @endforeach
</div>
