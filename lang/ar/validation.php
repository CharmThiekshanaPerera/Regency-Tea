<?php

/*
 * Only the rules EnquiryRequest actually uses (see
 * app/Http/Requests/EnquiryRequest.php) — missing keys fall back to the
 * English defaults bundled with the framework, so this stays a small,
 * confidently-translated subset rather than a full 200-line file.
 */
return [
    'required' => 'حقل :attribute مطلوب.',
    'email' => 'يجب أن يكون حقل :attribute بريدًا إلكترونيًا صالحًا.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'array' => 'يجب أن يكون حقل :attribute قائمة.',

    'max' => [
        'string' => 'يجب ألا يتجاوز حقل :attribute :max حرفًا.',
    ],
    'min' => [
        'string' => 'يجب أن يحتوي حقل :attribute على :min أحرف على الأقل.',
    ],

    'attributes' => [
        'name'    => 'الاسم',
        'email'   => 'البريد الإلكتروني',
        'company' => 'الشركة',
        'country' => 'الدولة',
        'subject' => 'الموضوع',
        'message' => 'الرسالة',
    ],
];
