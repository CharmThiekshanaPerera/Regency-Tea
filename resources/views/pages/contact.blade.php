@extends('layouts.app')
@section('title', 'Contact — '.config('regency.company.name'))
@section('meta_description', 'Contact Regency Teas for wholesale, private label and export enquiries.')

@section('content')
<x-breadcrumbs :items="['Contact' => null]" />

<div class="mx-auto max-w-7xl px-4 py-12">
    <div class="grid gap-12 lg:grid-cols-2">
        <div>
            <h1 class="text-4xl font-semibold tracking-tight">Get in touch</h1>
            <p class="mt-4 text-lg text-stone-600">
                Wholesale, HORECA and private-label enquiries are all welcome. Tell us what you need
                and we will come back to you within two business days.
            </p>

            @if (session('status'))
                <div role="status" class="mt-6 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert" class="mt-6 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-900">
                    <p class="font-medium">Please check the form:</p>
                    <ul class="mt-1 list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('contact.store') }}" class="mt-8 space-y-5">
                @csrf

                {{-- honeypot: bots fill this, humans never see it --}}
                <div class="hidden" aria-hidden="true">
                    <label for="website">Website</label>
                    <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                @if (request('product'))
                    <input type="hidden" name="source" value="product">
                    <div class="rounded-lg border border-stone-200 bg-stone-50 px-4 py-3 text-sm">
                        Enquiry about: <strong>{{ request('product') }}</strong>
                        @if (request('code')) <span class="text-stone-500">(Item Code {{ request('code') }})</span> @endif
                    </div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium">Name <span class="text-red-600">*</span></label>
                        <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name"
                               class="mt-1 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium">Email <span class="text-red-600">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                               class="mt-1 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-medium">Company</label>
                        <input id="company" name="company" value="{{ old('company') }}" autocomplete="organization"
                               class="mt-1 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </div>
                    <div>
                        <label for="country" class="block text-sm font-medium">Country</label>
                        <input id="country" name="country" value="{{ old('country') }}" autocomplete="country-name"
                               class="mt-1 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium">Subject</label>
                    <input id="subject" name="subject" value="{{ old('subject') }}"
                           class="mt-1 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium">Message <span class="text-red-600">*</span></label>
                    <textarea id="message" name="message" rows="6" required
                              class="mt-1 w-full rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">{{ old('message') }}</textarea>
                </div>

                <button type="submit"
                        class="rounded-full bg-emerald-800 px-8 py-3 font-semibold text-white hover:bg-emerald-900">
                    Send enquiry
                </button>
            </form>
        </div>

        <div class="space-y-8">
            @if ($page?->body)
                <div class="prose prose-stone max-w-none">{!! $page->body !!}</div>
            @endif

            <div class="overflow-hidden rounded-xl border border-stone-200">
                <iframe title="Regency Teas location" loading="lazy" class="h-80 w-full border-0"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps?q=Regency+Teas+Sri+Lanka&output=embed"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection
