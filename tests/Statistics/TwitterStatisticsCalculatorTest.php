<?php

namespace App\Tests\Statistics;

use DateTime;
use App\Utility\DateHelper;
use App\Entity\TwitterAccount;
use App\Statistics\TwitterStatisticsCalculator;

class TwitterStatisticsCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function newFollowersPerWeek_return_proper_followers_per_week()
    {
        $dateHelper = $this->createMock(DateHelper::class);
        $dateHelper
            ->method('weeksBetweenDates')
            ->willReturn(52)
            ;

        $twitterStatisticsCalculator = new TwitterStatisticsCalculator($dateHelper);

        $lastRecord = $this->createMock(TwitterAccount::class);
        $lastRecord->method('getFollowerCount')->willReturn(1000);
        $lastRecord->method('getCreatedAt')->willReturn(new \DateTime('2021-01-01'));

        $currentFollowerCount = 2000;
        $checkDate = new DateTime('2022-01-01');

        $result = $twitterStatisticsCalculator->newFollowersPerWeek($lastRecord, $currentFollowerCount, $checkDate);

        $this->assertEquals(19, $result);
    }

    /**
     * @test
     */
    public function newFollowersPerWeek_return_zero_followers_when_no_last_record()
    {
        $twitterStatisticsCalculator = new TwitterStatisticsCalculator(new DateHelper());

        $result = $twitterStatisticsCalculator->newFollowersPerWeek(null, 2000, date_create('2022-01-01'));

        $this->assertEquals(0, $result);
    }
}