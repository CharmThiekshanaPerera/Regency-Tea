@extends('layouts.app')
@section('title', 'All Teas — '.config('regency.company.name'))
@section('meta_description', 'Browse the complete Regency Teas catalogue — Ceylon black, green, herbal, wellness and flavoured teas for wholesale and private label.')

@section('content')
<x-breadcrumbs :items="['Products' => null]" />
<div class="mx-auto max-w-7xl px-4 py-10">
    <h1 class="text-4xl font-semibold tracking-tight">All Teas</h1>
    <p class="mt-3 max-w-2xl text-stone-600">
        Our complete range of pure Ceylon tea, available for wholesale, HORECA and private label.
    </p>
    @include('catalogue.partials.listing')
</div>
@endsection
