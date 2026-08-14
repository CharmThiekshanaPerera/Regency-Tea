<?php

/*
 * Only the rules EnquiryRequest actually uses (see
 * app/Http/Requests/EnquiryRequest.php) — missing keys fall back to the
 * English defaults bundled with the framework, so this stays a small,
 * confidently-translated subset rather than a full 200-line file.
 */
return [
    'required' => ':attribute අවශ්‍යයි.',
    'email' => ':attribute වලංගු විද්‍යුත් තැපැල් ලිපිනයක් විය යුතුය.',
    'string' => ':attribute අකුරු විය යුතුය.',
    'array' => ':attribute ලැයිස්තුවක් විය යුතුය.',

    'max' => [
        'string' => ':attribute අකුරු :max කට වඩා වැඩි විය නොහැක.',
    ],
    'min' => [
        'string' => ':attribute අවම වශයෙන් අකුරු :min ක් විය යුතුය.',
    ],

    'attributes' => [
        'name'    => 'නම',
        'email'   => 'විද්‍යුත් තැපෑල',
        'company' => 'සමාගම',
        'country' => 'රට',
        'subject' => 'මාතෘකාව',
        'message' => 'පණිවිඩය',
    ],
];
