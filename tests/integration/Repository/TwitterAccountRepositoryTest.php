<?php

namespace App\Tests\integration\Repository;

use App\Entity\TwitterAccount;
use App\Tests\DatabaseDependantTestCase;
use App\Repository\TwitterAccountRepository;

class TwitterAccountRepositoryTest extends DatabaseDependantTestCase
{
    private TwitterAccountRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->entityManager->getRepository(TwitterAccount::class);
    }

    /** @test */
    public function lastRecord_returns_proper_data()
    {
        $accountId = 99999;
        $previousAccount = new TwitterAccount();
        $previousAccount->setTwitterAccountId($accountId);
        $previousAccount->setUsername('phpunit');
        $previousAccount->setFollowerCount(1000);
        $previousAccount->setCreatedAt(date_create_immutable('2021-01-01'));
        $this->entityManager->persist($previousAccount);

        $currentAccount = new TwitterAccount();
        $currentAccount->setTwitterAccountId($accountId);
        $currentAccount->setUsername('phpunit');
        $currentAccount->setFollowerCount(1000);
        $currentAccount->setCreatedAt(date_create_immutable('2022-01-01'));
        $this->entityManager->persist($currentAccount);
        $this->entityManager->flush();

        $result = $this->repository->lastRecord($accountId);
        $this->assertInstanceOf(TwitterAccount::class, $result);
        $this->assertEquals($currentAccount->getId(), $result->getId());
        $this->assertEquals($currentAccount->getTwitterAccountId(), $result->getTwitterAccountId());
    }

    /** @test */
    public function lastRecord_returns_null_when_no_record_found()
    {
        $result = $this->repository->lastRecord(99999);
        $this->assertNull($result);
    }

    /** @test */
    public function addNewAccount_add_new_twitter_account()
    {
        $userData = [
            'id' => 12345,
            'username' => 'phpunit',
            'tweet_count' => 100,
            'listed_count' => 50,
            'following_count' => 200,
            'followers_count' => 300,
            'newFollowersPerWeek' => 10,
        ];

        $this->repository->addNewAccount($userData);
        $this->entityManager->flush();

        $result = $this->repository->findBy(['twitterAccountId' => $userData['id']]);
        $this->assertCount(1, $result);
    }
}
