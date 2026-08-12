@component('mail::message')
# New enquiry

**From:** {{ $enquiry->name }} ({{ $enquiry->email }})
@if ($enquiry->company)
**Company:** {{ $enquiry->company }}
@endif
@if ($enquiry->country)
**Country:** {{ $enquiry->country }}
@endif
@if ($enquiry->phone)
**Phone:** {{ $enquiry->phone }}
@endif
@if ($enquiry->subject)
**Subject:** {{ $enquiry->subject }}
@endif

---

{{ $enquiry->message }}

@if ($enquiry->products)
**Products referenced:** {{ implode(', ', (array) $enquiry->products) }}
@endif

@component('mail::button', ['url' => url('/admin/enquiries')])
View in admin
@endcomponent

Received {{ $enquiry->created_at->format('j F Y, H:i') }}
@endcomponent
