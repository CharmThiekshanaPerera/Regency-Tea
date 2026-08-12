<?php

namespace App\Support;

/**
 * Splits a legacy WooCommerce product title into a base product name and a
 * pack-size descriptor.
 *
 * The old catalogue encoded pack size in the title, e.g.
 *   "ENGLISH ARISTOCRATIC - 100 TEA BAGS"
 *   "EARL GREY - 100G LOOSE TEA"
 *   "GREEN TEA - 20 PYRAMID TEA BAGS"
 *
 * Analysis of all 434 published products: 394 titles carry a pack suffix,
 * yielding 289 distinct base products (39 of which have multiple pack sizes).
 */
class PackSizeParser
{
    /** Matches the trailing " - <pack size>" portion of a legacy title. */
    private const PATTERN = '/\s*[-–—]\s*(
        \d+\s*(?:X\s*\d+\s*(?:G|GM|GRAMS?)?\s*)?
        (?:FOIL\s+ENVELOPE\s+)?
        (?:INDIVIDUALLY\s+WRAPPED\s+)?
        (?:TEA\s*BAGS?|BAGS?|SACHETS?|PYRAMID[\w\s]*|STICKS?|CUPS?|
           G\b|GM\b|GRAMS?\b|KG\b)
        .*
    )$/xi';

    /** Normalise inconsistent base names so variants group correctly. */
    private const BASE_ALIASES = [
        'ENGLISH ARISTOCRATIC TEA'   => 'ENGLISH ARISTOCRATIC',
        'ENGLISH ROYAL BLEND TEA'    => 'ENGLISH ROYAL BLEND',
        'ENGLISH GREEN TEA LEAVES'   => 'ENGLISH GREEN TEA',
        'EARL GREY TEA'              => 'EARL GREY',
        'ENGLISH BREAKFAST TEA'      => 'ENGLISH BREAKFAST',
    ];

    public static function parse(string $title): array
    {
        $title = trim(html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        if (! preg_match(self::PATTERN, $title, $m, PREG_OFFSET_CAPTURE)) {
            return [
                'base'      => self::canonical($title),
                'pack_size' => null,
                'format'    => null,
                'quantity'  => null,
                'weight_g'  => null,
            ];
        }

        $base = self::canonical(rtrim(substr($title, 0, $m[0][1]), " -–—\t"));
        $pack = trim($m[1][0]);

        return [
            'base'      => $base,
            'pack_size' => $pack,
            'format'    => self::format($pack),
            'quantity'  => self::quantity($pack),
            'weight_g'  => self::weight($pack),
        ];
    }

    private static function canonical(string $base): string
    {
        $base = preg_replace('/\s+/', ' ', trim($base));

        return self::BASE_ALIASES[strtoupper($base)] ?? $base;
    }

    /** Bucket the free-text pack description into a controlled vocabulary. */
    private static function format(string $pack): string
    {
        return match (true) {
            (bool) preg_match('/PYRAMID/i', $pack)              => 'pyramid',
            (bool) preg_match('/LOOSE\s*TEA/i', $pack)          => 'loose_tea',
            (bool) preg_match('/SACHET/i', $pack)               => 'sachets',
            (bool) preg_match('/STICK/i', $pack)                => 'sticks',
            (bool) preg_match('/TEA\s*BAGS?/i', $pack)          => 'tea_bags',
            (bool) preg_match('/\d+\s*(?:G|GM|GRAMS?|KG)\b/i', $pack) => 'loose_tea',
            default                                             => 'other',
        };
    }

    /**
     * Leading count, e.g. "100 TEA BAGS" -> 100. Ignores gram weights.
     *
     * The `(?!\d)` after the capture is load-bearing: without it, `\d+` happily
     * backtracks so that "100G" matches as "10" followed by "0G", and a 100g
     * loose tea gets stored with a phantom count of 10.
     */
    private static function quantity(string $pack): ?int
    {
        if (preg_match('/^(\d+)(?!\d)\s*(?!G\b|GM\b|GRAMS?\b|KG\b)/i', $pack, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    /** Gram weight, e.g. "100G LOOSE TEA" -> 100, "1KG" -> 1000. */
    private static function weight(string $pack): ?int
    {
        if (preg_match('/(\d+(?:\.\d+)?)\s*KG\b/i', $pack, $m)) {
            return (int) round(((float) $m[1]) * 1000);
        }

        if (preg_match('/(\d+)\s*(?:G|GM|GRAMS?)\b/i', $pack, $m)) {
            return (int) $m[1];
        }

        return null;
    }
}
