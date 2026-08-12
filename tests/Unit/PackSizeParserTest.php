<?php

namespace Tests\Unit;

use App\Support\PackSizeParser;
use PHPUnit\Framework\TestCase;

class PackSizeParserTest extends TestCase
{
    public function test_it_splits_a_pack_size_from_the_title(): void
    {
        $r = PackSizeParser::parse('ENGLISH ARISTOCRATIC - 100 TEA BAGS');

        $this->assertSame('ENGLISH ARISTOCRATIC', $r['base']);
        $this->assertSame('100 TEA BAGS', $r['pack_size']);
        $this->assertSame('tea_bags', $r['format']);
        $this->assertSame(100, $r['quantity']);
        $this->assertNull($r['weight_g']);
    }

    public function test_it_reads_gram_weights(): void
    {
        $r = PackSizeParser::parse('EARL GREY - 100G LOOSE TEA');

        $this->assertSame('EARL GREY', $r['base']);
        $this->assertSame('loose_tea', $r['format']);
        $this->assertSame(100, $r['weight_g']);
        $this->assertNull($r['quantity']);
    }

    public function test_it_converts_kilograms(): void
    {
        $r = PackSizeParser::parse('BULK CEYLON - 1KG LOOSE TEA');

        $this->assertSame(1000, $r['weight_g']);
    }

    /**
     * Regression: `\d+` used to backtrack so that "100G" matched as "10"
     * followed by "0G", giving a 100g loose tea a phantom unit count of 10.
     *
     * @dataProvider gramPacks
     */
    public function test_gram_weights_never_produce_a_unit_count(string $pack, int $grams): void
    {
        $r = PackSizeParser::parse("SOME TEA - {$pack}");

        $this->assertNull($r['quantity'], "{$pack} should have no unit count");
        $this->assertSame($grams, $r['weight_g']);
    }

    public static function gramPacks(): array
    {
        return [
            ['50G LOOSE TEA', 50],
            ['80G LOOSE TEA TIN', 80],
            ['100G LOOSE TEA', 100],
            ['200G LOOSE TEA', 200],
            ['230G LOOSE TEA PLASTIC JAR', 230],
            ['400G LOOSE TEA SLIP ON LID TIN', 400],
            ['1KG LOOSE TEA', 1000],
        ];
    }

    /** A count and a gram weight are mutually exclusive across the whole catalogue. */
    public function test_quantity_and_weight_are_never_both_set(): void
    {
        foreach ([
            '25 FOIL ENVELOPE TEA BAGS', '100 TEA BAGS', '20 PYRAMID TEA BAGS',
            '100G LOOSE TEA', '1KG LOOSE TEA', '400G LOOSE TEA SLIP ON LID TIN',
        ] as $pack) {
            $r = PackSizeParser::parse("SOME TEA - {$pack}");

            $this->assertFalse(
                $r['quantity'] !== null && $r['weight_g'] !== null,
                "{$pack} produced both a count and a weight"
            );
        }
    }

    public function test_it_detects_pyramid_bags(): void
    {
        $r = PackSizeParser::parse('GREEN TEA - 20 PYRAMID TEA BAGS');

        $this->assertSame('pyramid', $r['format']);
        $this->assertSame(20, $r['quantity']);
    }

    public function test_it_handles_foil_envelope_packs(): void
    {
        $r = PackSizeParser::parse('PASSION FRUIT - 500 FOIL ENVELOPE TEA BAGS');

        $this->assertSame('PASSION FRUIT', $r['base']);
        $this->assertSame(500, $r['quantity']);
        $this->assertSame('tea_bags', $r['format']);
    }

    public function test_titles_without_a_pack_size_are_left_alone(): void
    {
        $r = PackSizeParser::parse('CEYLON GOLD GIFT SET');

        $this->assertSame('CEYLON GOLD GIFT SET', $r['base']);
        $this->assertNull($r['pack_size']);
    }

    /**
     * The legacy catalogue names the same line two ways. Without the alias
     * these split into two products instead of one with 17 variants.
     */
    public function test_inconsistent_base_names_are_normalised(): void
    {
        $a = PackSizeParser::parse('ENGLISH ARISTOCRATIC TEA - 500 TEA BAGS');
        $b = PackSizeParser::parse('ENGLISH ARISTOCRATIC - 25 TEA BAGS');

        $this->assertSame($a['base'], $b['base']);
    }

    public function test_it_decodes_html_entities(): void
    {
        $r = PackSizeParser::parse('BLACK TEA &amp; GINGER - 25 TEA BAGS');

        $this->assertSame('BLACK TEA & GINGER', $r['base']);
    }
}
