<?php

namespace App\Statistics;

use App\Utility\DateHelper;
use App\Entity\TwitterAccount;

class TwitterStatisticsCalculator
{
    public function __construct(
        private DateHelper $dateHelper,
    ) {
    }

    public function newFollowersPerWeek(
        ?TwitterAccount $lastRecord,
        int $currentFollowerCount,
        \DateTimeInterface $checkDate
    ): int
    {
        if (!$lastRecord) {
            return 0;
        }

        $followerCountDifference = $currentFollowerCount - $lastRecord->getFollowerCount();

        $fullWeeks = $this->dateHelper->weeksBetweenDates($checkDate, $lastRecord->getCreatedAt());

        $newFollowersPerWeek = $followerCountDifference / max($fullWeeks, 1);

        return (int) $newFollowersPerWeek;
    }
}