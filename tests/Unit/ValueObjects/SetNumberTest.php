<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\SetNumber;
use InvalidArgumentException;
use Tests\TestCase;

class SetNumberTest extends TestCase
{
    public function test_create_valid_set_number(): void
    {
        $setNumber = new SetNumber(3);

        $this->assertEquals(3, $setNumber->getValue());
    }

    public function test_create_minimum_valid_set_number(): void
    {
        $setNumber = new SetNumber(1);

        $this->assertEquals(1, $setNumber->getValue());
    }

    public function test_create_set_number_with_zero_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Set number must be 1 or greater');

        new SetNumber(0);
    }

    public function test_create_set_number_with_negative_value_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Set number must be 1 or greater');

        new SetNumber(-2);
    }

    public function test_equals(): void
    {
        $setNumber1 = new SetNumber(2);
        $setNumber2 = new SetNumber(2);
        $setNumber3 = new SetNumber(3);

        $this->assertTrue($setNumber1->equals($setNumber2));
        $this->assertFalse($setNumber1->equals($setNumber3));
    }

    public function test_next(): void
    {
        $setNumber = new SetNumber(2);

        $next = $setNumber->next();

        $this->assertEquals(3, $next->getValue());
        // 元のオブジェクトは変更されない
        $this->assertEquals(2, $setNumber->getValue());
    }

    public function test_previous(): void
    {
        $setNumber = new SetNumber(3);

        $previous = $setNumber->previous();

        $this->assertEquals(2, $previous->getValue());
        // 元のオブジェクトは変更されない
        $this->assertEquals(3, $setNumber->getValue());
    }

    public function test_previous_from_one_throws_exception(): void
    {
        $setNumber = new SetNumber(1);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot create set number less than 1');

        $setNumber->previous();
    }

    public function test_is_greater_than(): void
    {
        $setNumber1 = new SetNumber(3);
        $setNumber2 = new SetNumber(2);
        $setNumber3 = new SetNumber(4);

        $this->assertTrue($setNumber1->isGreaterThan($setNumber2));
        $this->assertFalse($setNumber1->isGreaterThan($setNumber3));
        $this->assertFalse($setNumber1->isGreaterThan($setNumber1)); // 同じ値
    }

    public function test_is_less_than(): void
    {
        $setNumber1 = new SetNumber(3);
        $setNumber2 = new SetNumber(2);
        $setNumber3 = new SetNumber(4);

        $this->assertFalse($setNumber1->isLessThan($setNumber2));
        $this->assertTrue($setNumber1->isLessThan($setNumber3));
        $this->assertFalse($setNumber1->isLessThan($setNumber1)); // 同じ値
    }

    public function test_to_string(): void
    {
        $setNumber = new SetNumber(5);

        $this->assertEquals('5', $setNumber->toString());
        $this->assertEquals('5', (string) $setNumber);
    }
}