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

    'legacy_domain'  => 'regencyteas.com',
    'brand_aliases'  => [
        // lakmatea.com previously 301'd to the Lakma brand archive via .htaccess
        'lakmatea.com'     => '/brands/lakma',
        'www.lakmatea.com' => '/brands/lakma',
    ],
];
