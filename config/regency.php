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
     | Site chrome (nav, footer, forms) is translated for these locales via
     | lang/{locale}/site.php. Page and product CONTENT stays English-only —
     | translating 322 products and a dozen page templates needs real
     | translators, not a machine pass, so that's deliberately out of scope
     | here. Locale is a lightweight session flag (no /en/, /ru/ URL prefix),
     | so this trades SEO-grade hreflang routing for a same-URL, zero-risk
     | rollout across the existing route set.
     */
    'locales' => [
        'en' => ['native' => 'English', 'label' => 'English'],
        'si' => ['native' => 'සිංහල', 'label' => 'Sinhala'],
        'ru' => ['native' => 'Русский', 'label' => 'Russian'],
    ],

    'legacy_domain'  => 'regencyteas.com',
    'brand_aliases'  => [
        // lakmatea.com previously 301'd to the Lakma brand archive via .htaccess
        'lakmatea.com'     => '/brands/lakma',
        'www.lakmatea.com' => '/brands/lakma',
    ],
];
