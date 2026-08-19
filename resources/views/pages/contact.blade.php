@extends('layouts.app')
@section('title', 'Contact — '.config('regency.company.name'))
@section('meta_description', 'Contact Regency Teas for wholesale, private label and export enquiries — Colombo head office, plus representatives in Canada, Russia and the USA.')
@section('og_image', asset('images/contact/hero.jpg'))

@php
$offices = [
    ['country' => 'Canada', 'name' => 'G.P. International', 'lines' => ['796 Four Winds Way', 'Mississauga, ONT, L5R 3W8'], 'phone' => '416-948 0336', 'email' => 'sales.canada@hyleys.com'],
    ['country' => 'Russia', 'name' => 'Hyleys Tea — Eastern Europe & CIS', 'lines' => ['Bldg. 22, No. 24, Kashirskoe Shosse', 'Moscow, 115478'], 'phone' => '+7 495 956 8892', 'email' => 'regency@hyleys.ru'],
    ['country' => 'USA', 'name' => 'Mekor LLC', 'lines' => ['One Rockefeller Plaza, 11th Floor', 'New York, NY 10020'], 'phone' => '1-888-HYLEYS-T', 'email' => 'sales.usa@hyleys.com'],
];
@endphp

@section('content')

{{-- Hero --}}
<section class="relative isolate flex h-[42vh] min-h-[320px] items-center overflow-hidden bg-stone-950">
    <img src="{{ asset('images/contact/hero.jpg') }}" alt="{{ __('pages/contact.hero.image_alt') }}"
         class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-stone-950/95 via-stone-950/70 to-stone-950/40"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6" data-reveal>
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-emerald-300 ring-1 ring-white/20 backdrop-blur">
            {{ __('pages/contact.hero.badge') }}
        </span>
        <h1 class="mt-6 max-w-xl text-4xl font-semibold leading-tight tracking-tight text-white sm:text-5xl">
            {{ __('pages/contact.hero.heading') }}
        </h1>
        <p class="mt-4 max-w-lg text-lg text-stone-200">
            {{ __('pages/contact.hero.subheading') }}
        </p>
    </div>
</section>

{{-- Quick info --}}
<section class="border-b border-stone-200 bg-white py-14" data-reveal>
    <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => __('pages/contact.quick_info.phone_label'), 'value' => '+94 11 4713 518', 'href' => 'tel:+94114713518'],
            ['icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => __('pages/contact.quick_info.email_label'), 'value' => 'regencyt@regencyteas.com', 'href' => 'mailto:regencyt@regencyteas.com'],
            ['icon' => 'M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => __('pages/contact.quick_info.address_label'), 'value' => 'No. 359, Biyagama Road, Kelaniya, Sri Lanka 11600', 'href' => 'https://www.google.com/maps?q=Regency+Teas+Sri+Lanka'],
            ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => __('pages/contact.quick_info.hours_label'), 'value' => 'Mon–Fri, 8.30am – 5.00pm', 'href' => null],
        ] as $i => $item)
            <div class="flex items-start gap-4 rounded-xl border border-stone-200 p-5 transition hover:border-emerald-600 hover:shadow-md" style="--reveal-delay: {{ $i * 80 }}ms">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-stone-500">{{ $item['label'] }}</p>
                    @if ($item['href'])
                        <a href="{{ $item['href'] }}" class="mt-1 block text-sm font-medium text-stone-800 hover:text-emerald-700">{{ $item['value'] }}</a>
                    @else
                        <p class="mt-1 text-sm font-medium text-stone-800">{{ $item['value'] }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- Form + map --}}
<section class="py-20" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="grid gap-12 lg:grid-cols-2">
            <div>
                <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-700">{{ __('pages/contact.form.eyebrow') }}</h2>
                <p class="mt-2 text-3xl font-semibold tracking-tight">{{ __('pages/contact.form.heading') }}</p>

                @if (session('status'))
                    <div role="status" class="mt-6 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert" class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-900">
                        <p class="font-medium">{{ __('pages/contact.form.errors_heading') }}</p>
                        <ul class="mt-1 list-disc pl-5 text-sm">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('contact.store') }}" class="mt-8 space-y-5">
                    @csrf

                    {{-- honeypot: bots fill this, humans never see it --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website">{{ __('pages/contact.form.honeypot_label') }}</label>
                        <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    @if (request('product'))
                        <input type="hidden" name="source" value="product">
                        <div class="rounded-lg border border-stone-200 bg-stone-50 px-4 py-3 text-sm">
                            {{ __('pages/contact.form.enquiry_about') }} <strong>{{ request('product') }}</strong>
                            @if (request('code')) <span class="text-stone-500">{{ __('pages/contact.form.item_code', ['code' => request('code')]) }}</span> @endif
                        </div>
                    @endif

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium">{{ __('site.contact_form.name') }} <span class="text-red-600">*</span></label>
                            <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                                   class="mt-1.5 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium">{{ __('site.contact_form.email') }} <span class="text-red-600">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                                   class="mt-1.5 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        </div>
                        <div>
                            <label for="company" class="block text-sm font-medium">{{ __('site.contact_form.company') }}</label>
                            <input id="company" name="company" value="{{ old('company') }}" autocomplete="organization"
                                   class="mt-1.5 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium">{{ __('site.contact_form.country') }}</label>
                            <input id="country" name="country" value="{{ old('country') }}" autocomplete="country-name"
                                   class="mt-1.5 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-medium">{{ __('site.contact_form.subject') }}</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}"
                               class="mt-1.5 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-medium">{{ __('site.contact_form.message') }} <span class="text-red-600">*</span></label>
                        <textarea id="message" name="message" rows="6" required
                                  class="mt-1.5 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="rounded-full bg-emerald-800 px-8 py-3.5 font-semibold text-white shadow-lg shadow-emerald-900/20 transition hover:-translate-y-0.5 hover:bg-emerald-900">
                        {{ __('site.contact_form.send') }}
                    </button>
                </form>
            </div>

            <div class="space-y-6">
                {{-- The page's raw imported body duplicates the quick-info cards and global
                     offices below almost verbatim, so it's intentionally not rendered here. --}}
                <div class="overflow-hidden rounded-2xl border border-stone-200 shadow-lg">
                    <iframe title="{{ __('pages/contact.map.title') }}" loading="lazy" class="h-96 w-full border-0"
                            referrerpolicy="no-referrer-when-downgrade"
                            src="https://www.google.com/maps?q=Regency+Teas+Sri+Lanka&output=embed"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Global offices --}}
<section class="bg-stone-950 py-20 text-white" data-reveal>
    <div class="mx-auto max-w-7xl px-4">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-sm font-semibold uppercase tracking-widest text-emerald-400">{{ __('pages/contact.offices.eyebrow') }}</h2>
            <p class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">{{ __('pages/contact.offices.heading') }}</p>
        </div>

        <ul role="list" class="mt-12 grid gap-6 sm:grid-cols-3">
            @foreach ($offices as $i => $office)
                <li class="rounded-xl border border-white/10 bg-white/5 p-6" style="--reveal-delay: {{ $i * 100 }}ms">
                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-400">{{ $office['country'] }}</span>
                    <h3 class="mt-2 text-lg font-medium">{{ $office['name'] }}</h3>
                    <p class="mt-2 text-sm text-stone-400">
                        @foreach ($office['lines'] as $line){{ $line }}<br>@endforeach
                    </p>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $office['phone']) }}" class="mt-3 block text-sm text-stone-300 hover:text-emerald-400">{{ $office['phone'] }}</a>
                    <a href="mailto:{{ $office['email'] }}" class="mt-1 block text-sm text-stone-300 hover:text-emerald-400">{{ $office['email'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</section>

@endsection
