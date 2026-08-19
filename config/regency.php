<?php

return [
    /*
     | Phase C gate. The legacy WordPress export contains NO pricing data
     | (see discovery/PHASE2-MIGRATION-PLAN.md §0), so cart and checkout stay
     | switched off until a price list has been imported.
     */
    'commerce_enabled' => env('COMMERCE_ENABLED', false),

    'enquiry_inbox' => env('ENQUIRY_INBOX', 'info@regencyteas.com'),

    'company' => [
        'name'    => 'Regency Teas (PVT) LTD',
        'tagline' => 'Pure Ceylon Tea Exporter in Sri Lanka',
    ],

    /*
     | Site chrome (nav, footer, forms) is translated via lang/{locale}/site.php.
     | Page, product and post CONTENT is translated too — see the
     | $translatable arrays on Product/Page/Post/Category/etc, backed by
     | spatie/laravel-translatable (JSON columns, one value per locale).
     | Locale is a lightweight session flag (no /en/, /ru/ URL prefix), so
     | this trades SEO-grade hreflang routing for a same-URL, zero-risk
     | rollout across the existing route set.
     |
     | 'rtl' marks locales that read right-to-left — see layouts/app.blade.php,
     | which sets the <html dir> attribute from it. This flips text direction
     | correctly but does NOT mirror the layout itself (icon positions, flex
     | ordering, margin/padding sides); that's a separate, much larger pass
     | across every Blade view and wasn't in scope here.
     */
    'locales' => [
        'en' => ['native' => 'English', 'label' => 'English'],
        'si' => ['native' => 'සිංහල', 'label' => 'Sinhala'],
        'ru' => ['native' => 'Русский', 'label' => 'Russian'],
        'zh' => ['native' => '简体中文', 'label' => 'Chinese (Simplified)'],
        'fr' => ['native' => 'Français', 'label' => 'French'],
        'ar' => ['native' => 'العربية', 'label' => 'Arabic', 'rtl' => true],
        'de' => ['native' => 'Deutsch', 'label' => 'German'],
    ],

    'legacy_domain'  => 'regencyteas.com',
    'brand_aliases'  => [
        // lakmatea.com previously 301'd to the Lakma brand archive via .htaccess
        'lakmatea.com'     => '/brands/lakma',
        'www.lakmatea.com' => '/brands/lakma',
    ],
];
