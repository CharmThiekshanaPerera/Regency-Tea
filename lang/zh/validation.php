<?php

/*
 * Only the rules EnquiryRequest actually uses (see
 * app/Http/Requests/EnquiryRequest.php) — missing keys fall back to the
 * English defaults bundled with the framework, so this stays a small,
 * confidently-translated subset rather than a full 200-line file.
 */
return [
    'required' => ':attribute 为必填项。',
    'email' => ':attribute 必须是有效的电子邮箱地址。',
    'string' => ':attribute 必须是字符串。',
    'array' => ':attribute 必须是列表。',

    'max' => [
        'string' => ':attribute 不能超过 :max 个字符。',
    ],
    'min' => [
        'string' => ':attribute 至少需要 :min 个字符。',
    ],

    'attributes' => [
        'name'    => '姓名',
        'email'   => '电子邮箱',
        'company' => '公司',
        'country' => '国家',
        'subject' => '主题',
        'message' => '留言',
    ],
];
