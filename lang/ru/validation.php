<?php

/*
 * Only the rules EnquiryRequest actually uses (see
 * app/Http/Requests/EnquiryRequest.php) — missing keys fall back to the
 * English defaults bundled with the framework, so this stays a small,
 * confidently-translated subset rather than a full 200-line file.
 */
return [
    'required' => 'Поле «:attribute» обязательно для заполнения.',
    'email' => 'Поле «:attribute» должно содержать действительный адрес электронной почты.',
    'string' => 'Поле «:attribute» должно быть строкой.',
    'array' => 'Поле «:attribute» должно быть списком.',

    'max' => [
        'string' => 'Поле «:attribute» не должно превышать :max символов.',
    ],
    'min' => [
        'string' => 'Поле «:attribute» должно содержать не менее :min символов.',
    ],

    'attributes' => [
        'name'    => 'Имя',
        'email'   => 'Эл. почта',
        'company' => 'Компания',
        'country' => 'Страна',
        'subject' => 'Тема',
        'message' => 'Сообщение',
    ],
];
