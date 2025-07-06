<?php

namespace Tests\Unit\ValueObjects;

use App\ValueObjects\TrainingDate;
use Carbon\Carbon;
use InvalidArgumentException;
use Tests\TestCase;

class TrainingDateTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // テスト用に固定日時を設定
        Carbon::setTestNow(Carbon::create(2025, 1, 15, 12, 0, 0));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_create_training_date_from_string(): void
    {
        $trainingDate = new TrainingDate('2025-01-10');

        $this->assertEquals('2025-01-10', $trainingDate->format());
    }

    public function test_create_training_date_from_carbon(): void
    {
        $carbon = Carbon::create(2025, 1, 10, 15, 30, 0);
        $trainingDate = new TrainingDate($carbon);

        $this->assertEquals('2025-01-10', $trainingDate->format());
        // 時刻は00:00:00にリセットされる
        $this->assertEquals('00:00:00', $trainingDate->getDate()->format('H:i:s'));
    }

    public function test_create_training_date_with_invalid_string_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format: invalid-date');

        new TrainingDate('invalid-date');
    }

    public function test_create_training_date_with_future_date_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Training date cannot be in the future');

        new TrainingDate('2025-01-20'); // 今日は2025-01-15
    }

    public function test_today(): void
    {
        $today = TrainingDate::today();

        $this->assertEquals('2025-01-15', $today->format());
    }

    public function test_yesterday(): void
    {
        $yesterday = TrainingDate::yesterday();

        $this->assertEquals('2025-01-14', $yesterday->format());
    }

    public function test_from_string(): void
    {
        $trainingDate = TrainingDate::fromString('2025-01-10');

        $this->assertEquals('2025-01-10', $trainingDate->format());
    }

    public function test_get_date(): void
    {
        $trainingDate = new TrainingDate('2025-01-10');
        $carbon = $trainingDate->getDate();

        $this->assertInstanceOf(Carbon::class, $carbon);
        $this->assertEquals('2025-01-10', $carbon->format('Y-m-d'));
        $this->assertEquals('00:00:00', $carbon->format('H:i:s'));
    }

    public function test_format(): void
    {
        $trainingDate = new TrainingDate('2025-01-10');

        $this->assertEquals('2025-01-10', $trainingDate->format());
        $this->assertEquals('10/01/2025', $trainingDate->format('d/m/Y'));
    }

    public function test_equals(): void
    {
        $date1 = new TrainingDate('2025-01-10');
        $date2 = new TrainingDate('2025-01-10');
        $date3 = new TrainingDate('2025-01-11');

        $this->assertTrue($date1->equals($date2));
        $this->assertFalse($date1->equals($date3));
    }

    public function test_is_before(): void
    {
        $date1 = new TrainingDate('2025-01-10');
        $date2 = new TrainingDate('2025-01-11');
        $date3 = new TrainingDate('2025-01-09');

        $this->assertTrue($date1->isBefore($date2));
        $this->assertFalse($date1->isBefore($date3));
        $this->assertFalse($date1->isBefore($date1));
    }

    public function test_is_after(): void
    {
        $date1 = new TrainingDate('2025-01-10');
        $date2 = new TrainingDate('2025-01-11');
        $date3 = new TrainingDate('2025-01-09');

        $this->assertFalse($date1->isAfter($date2));
        $this->assertTrue($date1->isAfter($date3));
        $this->assertFalse($date1->isAfter($date1));
    }

    public function test_days_between(): void
    {
        $date1 = new TrainingDate('2025-01-10');
        $date2 = new TrainingDate('2025-01-13');
        $date3 = new TrainingDate('2025-01-07');

        $this->assertEquals(3, $date1->daysBetween($date2));
        $this->assertEquals(3, $date1->daysBetween($date3));
        $this->assertEquals(0, $date1->daysBetween($date1));
    }

    public function test_is_within_days(): void
    {
        $date1 = new TrainingDate('2025-01-10');
        $date2 = new TrainingDate('2025-01-12');
        $date3 = new TrainingDate('2025-01-15');

        $this->assertTrue($date1->isWithinDays($date2, 3));
        $this->assertFalse($date1->isWithinDays($date3, 3));
        $this->assertTrue($date1->isWithinDays($date1, 0));
    }

    public function test_is_today(): void
    {
        $today = new TrainingDate('2025-01-15'); // テスト日時
        $yesterday = new TrainingDate('2025-01-14');

        $this->assertTrue($today->isToday());
        $this->assertFalse($yesterday->isToday());
    }

    public function test_is_yesterday(): void
    {
        $today = new TrainingDate('2025-01-15');
        $yesterday = new TrainingDate('2025-01-14');

        $this->assertFalse($today->isYesterday());
        $this->assertTrue($yesterday->isYesterday());
    }

    public function test_get_weekday(): void
    {
        $trainingDate = new TrainingDate('2025-01-13'); // 月曜日

        $this->assertEquals('Monday', $trainingDate->getWeekday());
    }

    public function test_to_string(): void
    {
        $trainingDate = new TrainingDate('2025-01-10');

        $this->assertEquals('2025-01-10', $trainingDate->toString());
        $this->assertEquals('2025-01-10', (string) $trainingDate);
    }
}