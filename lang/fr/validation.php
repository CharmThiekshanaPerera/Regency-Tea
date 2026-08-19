<?php

/*
 * Only the rules EnquiryRequest actually uses (see
 * app/Http/Requests/EnquiryRequest.php) — missing keys fall back to the
 * English defaults bundled with the framework, so this stays a small,
 * confidently-translated subset rather than a full 200-line file.
 */
return [
    'required' => 'Le champ :attribute est obligatoire.',
    'email' => 'Le champ :attribute doit être une adresse e-mail valide.',
    'string' => 'Le champ :attribute doit être une chaîne de caractères.',
    'array' => 'Le champ :attribute doit être une liste.',

    'max' => [
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],
    'min' => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],

    'attributes' => [
        'name'    => 'Nom',
        'email'   => 'E-mail',
        'company' => 'Entreprise',
        'country' => 'Pays',
        'subject' => 'Sujet',
        'message' => 'Message',
    ],
];
