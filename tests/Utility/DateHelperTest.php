<?php

namespace App\Tests\Utility;

use App\Utility\DateHelper;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    /**
     * @test
     */
    public function weeksBetweenDates_return_proper_weeks()
    {
        $dateOne = date_create('2021-10-01');
        $dateTwo = date_create('2022-10-01');

        $dateHelper = new DateHelper();
        $result = $dateHelper->weeksBetweenDates($dateOne, $dateTwo);

        $this->assertEquals(52, $result);
    }

    /**
     * @test
     */
    public function weeksBetweenDates_return_zero_weeks_when_same_date()
    {
        $dateOne = date_create('2023-10-01');
        $dateTwo = date_create('2023-10-01');

        $dateHelper = new DateHelper();
        $result = $dateHelper->weeksBetweenDates($dateOne, $dateTwo);

        $this->assertEquals(0, $result);
    }
}
