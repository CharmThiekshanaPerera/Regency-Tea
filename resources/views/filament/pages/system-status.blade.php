<x-filament-panels::page>
    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($this->getChecks() as $check)
            @php
                $ok = $check['ok'];
                $border = $ok ? '#86efac' : '#fca5a5';
                $bg = $ok ? '#f0fdf4' : '#fef2f2';
                $dot = $ok ? '#16a34a' : '#dc2626';
                $text = $ok ? '#166534' : '#991b1b';
            @endphp
            <div class="rounded-xl border p-4" style="border-color: {{ $border }}; background-color: {{ $bg }};">
                <div class="flex items-start gap-3">
                    <span class="mt-1 flex h-3 w-3 shrink-0 items-center justify-center rounded-full"
                          style="background-color: {{ $dot }};" aria-hidden="true"></span>
                    <div>
                        <p class="font-medium" style="color: {{ $text }};">
                            {{ $check['name'] }} — {{ $ok ? 'OK' : 'ATTENTION NEEDED' }}
                        </p>
                        <p class="mt-0.5 text-sm text-gray-600">{{ $check['detail'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
