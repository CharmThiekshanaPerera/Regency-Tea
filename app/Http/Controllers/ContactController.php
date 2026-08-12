<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnquiryRequest;
use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact', [
            'page' => Page::published()->where('slug', 'contact')->first(),
        ]);
    }

    public function store(EnquiryRequest $request): RedirectResponse
    {
        $enquiry = Enquiry::create($request->validated() + [
            'ip' => $request->ip(),
        ]);

        Mail::to(config('regency.enquiry_inbox'))->send(new EnquiryReceived($enquiry));

        return back()->with('status', 'Thank you — we will be in touch shortly.');
    }
}
