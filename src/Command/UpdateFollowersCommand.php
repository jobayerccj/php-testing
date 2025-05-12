<?php

namespace App\Command;

use App\Http\TwitterClient;
use App\Entity\TwitterAccount;
use Doctrine\ORM\EntityManagerInterface;
use App\Statistics\TwitterStatisticsCalculator;
use App\Utility\DateHelper;
use DateTimeInterface;

class UpdateFollowersCommand
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TwitterClient $twitterClient,
        private array $accountIds,
        private DateTimeInterface $processDate,
    ) {
        $this->entityManager = $entityManager;
        $this->twitterClient = $twitterClient;
        $this->accountIds = $accountIds;
        $this->processDate = $processDate;
    }

    public function execute(): void
    {
        foreach ($this->accountIds as $accountId) {

            $user = $this->twitterClient->getUserById($accountId);

            $repo = $this->entityManager->getRepository(TwitterAccount::class);
            $lastRecord = $repo->lastRecord($accountId);
            
            $user['newFollowersPerWeek'] = (new TwitterStatisticsCalculator(new DateHelper()))
                ->newFollowersPerWeek(
                    $lastRecord,
                    $user['followers_count'],
                    $this->processDate
            );

            $repo->addNewAccount($user);
        }

        $this->entityManager->flush();
    }
}
