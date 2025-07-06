<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\Reps;
use InvalidArgumentException;
use Tests\TestCase;

class RepsTest extends TestCase
{
    public function test_create_valid_reps(): void
    {
        $reps = new Reps(10);

        $this->assertEquals(10, $reps->getValue());
    }

    public function test_create_minimum_valid_reps(): void
    {
        $reps = new Reps(1);

        $this->assertEquals(1, $reps->getValue());
    }

    public function test_create_reps_with_zero_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Reps must be 1 or greater');

        new Reps(0);
    }

    public function test_create_reps_with_negative_value_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Reps must be 1 or greater');

        new Reps(-5);
    }

    public function test_equals(): void
    {
        $reps1 = new Reps(10);
        $reps2 = new Reps(10);
        $reps3 = new Reps(15);

        $this->assertTrue($reps1->equals($reps2));
        $this->assertFalse($reps1->equals($reps3));
    }

    public function test_add(): void
    {
        $reps1 = new Reps(5);
        $reps2 = new Reps(3);

        $result = $reps1->add($reps2);

        $this->assertEquals(8, $result->getValue());
        // 元のオブジェクトは変更されない
        $this->assertEquals(5, $reps1->getValue());
        $this->assertEquals(3, $reps2->getValue());
    }

    public function test_is_greater_than(): void
    {
        $reps1 = new Reps(10);
        $reps2 = new Reps(5);
        $reps3 = new Reps(15);

        $this->assertTrue($reps1->isGreaterThan($reps2));
        $this->assertFalse($reps1->isGreaterThan($reps3));
        $this->assertFalse($reps1->isGreaterThan($reps1)); // 同じ値
    }

    public function test_is_less_than(): void
    {
        $reps1 = new Reps(10);
        $reps2 = new Reps(5);
        $reps3 = new Reps(15);

        $this->assertFalse($reps1->isLessThan($reps2));
        $this->assertTrue($reps1->isLessThan($reps3));
        $this->assertFalse($reps1->isLessThan($reps1)); // 同じ値
    }

    public function test_to_string(): void
    {
        $reps = new Reps(12);

        $this->assertEquals('12', $reps->toString());
        $this->assertEquals('12', (string) $reps);
    }
}