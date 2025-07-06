<?php

namespace Tests\Unit\ValueObjects;

use App\Models\WeightUnit;
use App\ValueObjects\Weight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class WeightTest extends TestCase
{
    use RefreshDatabase;

    private WeightUnit $kgUnit;
    private WeightUnit $lbUnit;
    private WeightUnit $gUnit;

    protected function setUp(): void
    {
        parent::setUp();
        
        // テスト用の重量単位を作成
        $this->kgUnit = WeightUnit::create([
            'name' => 'Kilogram',
            'symbol' => 'kg',
            'conversion_rate' => 1.0,
        ]);
        
        $this->lbUnit = WeightUnit::create([
            'name' => 'Pound',
            'symbol' => 'lb',
            'conversion_rate' => 0.453592,
        ]);
        
        $this->gUnit = WeightUnit::create([
            'name' => 'Gram',
            'symbol' => 'g',
            'conversion_rate' => 0.001,
        ]);
    }

    public function test_create_valid_weight(): void
    {
        $weight = new Weight(50.5, $this->kgUnit);

        $this->assertEquals(50.5, $weight->getValue());
        $this->assertEquals($this->kgUnit->id, $weight->getUnitId());
        $this->assertEquals('kg', $weight->getUnitSymbol());
    }

    public function test_create_weight_with_zero_value(): void
    {
        $weight = new Weight(0.0, $this->kgUnit);

        $this->assertEquals(0.0, $weight->getValue());
    }

    public function test_create_weight_with_negative_value_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Weight value must be 0 or greater');

        new Weight(-10.5, $this->kgUnit);
    }

    public function test_get_unit(): void
    {
        $weight = new Weight(75.0, $this->kgUnit);

        $unit = $weight->getUnit();

        $this->assertInstanceOf(WeightUnit::class, $unit);
        $this->assertEquals($this->kgUnit->id, $unit->id);
        $this->assertEquals('kg', $unit->symbol);
    }

    public function test_convert_to_same_unit_returns_same_object(): void
    {
        $weight = new Weight(50.0, $this->kgUnit);

        $converted = $weight->convertTo($this->kgUnit);

        $this->assertSame($weight, $converted);
    }

    public function test_convert_kg_to_pounds(): void
    {
        $weight = new Weight(10.0, $this->kgUnit); // 10kg

        $converted = $weight->convertTo($this->lbUnit);

        $this->assertEqualsWithDelta(22.046, $converted->getValue(), 0.01);
        $this->assertEquals('lb', $converted->getUnitSymbol());
    }

    public function test_convert_pounds_to_kg(): void
    {
        $weight = new Weight(22.046, $this->lbUnit); // ~10kg

        $converted = $weight->convertTo($this->kgUnit);

        $this->assertEqualsWithDelta(10.0, $converted->getValue(), 0.01);
        $this->assertEquals('kg', $converted->getUnitSymbol());
    }

    public function test_convert_kg_to_grams(): void
    {
        $weight = new Weight(2.5, $this->kgUnit); // 2.5kg

        $converted = $weight->convertTo($this->gUnit);

        $this->assertEquals(2500.0, $converted->getValue());
        $this->assertEquals('g', $converted->getUnitSymbol());
    }

    public function test_convert_grams_to_kg(): void
    {
        $weight = new Weight(1500.0, $this->gUnit); // 1500g

        $converted = $weight->convertTo($this->kgUnit);

        $this->assertEquals(1.5, $converted->getValue());
        $this->assertEquals('kg', $converted->getUnitSymbol());
    }

    public function test_to_kg(): void
    {
        $weight = new Weight(22.046, $this->lbUnit);

        $kgWeight = $weight->toKg();

        $this->assertEqualsWithDelta(10.0, $kgWeight->getValue(), 0.01);
        $this->assertEquals('kg', $kgWeight->getUnitSymbol());
    }

    public function test_to_kg_when_already_kg(): void
    {
        $weight = new Weight(10.0, $this->kgUnit);

        $kgWeight = $weight->toKg();

        // 同じ値かどうかを確認（オブジェクト参照は異なる可能性がある）
        $this->assertEquals($weight->getValue(), $kgWeight->getValue());
        $this->assertEquals($weight->getUnitSymbol(), $kgWeight->getUnitSymbol());
    }

    public function test_equals_same_unit(): void
    {
        $weight1 = new Weight(50.0, $this->kgUnit);
        $weight2 = new Weight(50.0, $this->kgUnit);
        $weight3 = new Weight(60.0, $this->kgUnit);

        $this->assertTrue($weight1->equals($weight2));
        $this->assertFalse($weight1->equals($weight3));
    }

    public function test_equals_different_units(): void
    {
        $weight1 = new Weight(10.0, $this->kgUnit); // 10kg
        $weight2 = new Weight(22.046, $this->lbUnit); // ~10kg
        $weight3 = new Weight(20.0, $this->lbUnit); // ~9kg

        $this->assertTrue($weight1->equals($weight2));
        $this->assertFalse($weight1->equals($weight3));
    }

    public function test_equals_with_precision(): void
    {
        $weight1 = new Weight(10.0, $this->kgUnit);
        $weight2 = new Weight(10.0005, $this->kgUnit); // 0.5g difference
        $weight3 = new Weight(10.002, $this->kgUnit); // 2g difference

        $this->assertTrue($weight1->equals($weight2)); // Within precision
        $this->assertFalse($weight1->equals($weight3)); // Outside precision
    }

    public function test_to_string(): void
    {
        $weight = new Weight(75.5, $this->kgUnit);

        $this->assertEquals('75.5 kg', $weight->toString());
        $this->assertEquals('75.5 kg', (string) $weight);
    }

    public function test_to_string_with_different_unit(): void
    {
        $weight = new Weight(165.5, $this->lbUnit);

        $this->assertEquals('165.5 lb', $weight->toString());
    }
}