<?php

/*
 * Only the rules EnquiryRequest actually uses (see
 * app/Http/Requests/EnquiryRequest.php) — missing keys fall back to the
 * English defaults bundled with the framework, so this stays a small,
 * confidently-translated subset rather than a full 200-line file.
 */
return [
    'required' => 'Das Feld :attribute ist erforderlich.',
    'email' => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'string' => 'Das Feld :attribute muss eine Zeichenkette sein.',
    'array' => 'Das Feld :attribute muss eine Liste sein.',

    'max' => [
        'string' => 'Das Feld :attribute darf :max Zeichen nicht überschreiten.',
    ],
    'min' => [
        'string' => 'Das Feld :attribute muss mindestens :min Zeichen enthalten.',
    ],

    'attributes' => [
        'name'    => 'Name',
        'email'   => 'E-Mail',
        'company' => 'Unternehmen',
        'country' => 'Land',
        'subject' => 'Betreff',
        'message' => 'Nachricht',
    ],
];
