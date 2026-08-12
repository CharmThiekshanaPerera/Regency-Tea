<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** Replaces Contact Form 7. Honeypot + validation, no plugin. */
class EnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Bots fill hidden fields; humans do not.
        return blank($this->input('website'));
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:120'],
            'email'    => ['required', 'email:rfc,dns', 'max:190'],
            'company'  => ['nullable', 'string', 'max:150'],
            'country'  => ['nullable', 'string', 'max:80'],
            'phone'    => ['nullable', 'string', 'max:40'],
            'subject'  => ['nullable', 'string', 'max:190'],
            'message'  => ['required', 'string', 'min:10', 'max:5000'],
            'products' => ['nullable', 'array'],
            'source'   => ['nullable', 'string', 'max:40'],
        ];
    }
}
