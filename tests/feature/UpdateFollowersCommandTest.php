<?php

namespace App\Tests\Feature;

use App\Http\TwitterClient;
use App\Entity\TwitterAccount;
use App\Command\UpdateFollowersCommand;
use App\Http\ApplicationClientInterface;
use App\Tests\DatabaseDependantTestCase;

class UpdateFollowersCommandTest extends DatabaseDependantTestCase
{
    private const ACCOUNT1 = 12345;
    private const ACCOUNT2 = 67890;

    /** @test */
    public function updateFollowersCommand_is_completing_all_tasks()
    {
        $applicationClient = $this->createMock(ApplicationClientInterface::class);
        $twitterClient = new TwitterClient($applicationClient);
        
        $gctAccount = new TwitterAccount();
        $gctAccount->setUsername('user1');
        $gctAccount->setTwitterAccountId(self::ACCOUNT1);
        $gctAccount->setFollowerCount(1000);
        $gctAccount->setCreatedAt(date_create_immutable('2021-01-01'));
        $this->entityManager->persist($gctAccount);

        $phpUnitAcct = new TwitterAccount();
        $phpUnitAcct->setUsername('user2');
        $phpUnitAcct->setTwitterAccountId(self::ACCOUNT2);
        $phpUnitAcct->setFollowerCount(2000);
        $phpUnitAcct->setCreatedAt(date_create_immutable('2021-01-01'));
        $this->entityManager->persist($phpUnitAcct);
 
         $this->entityManager->flush();

        $applicationClient->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(
                json_encode([
                    'data' => [
                        'id' => self::ACCOUNT1,
                        'username' => 'user1',
                        'public_metrics' => [
                            'followers_count' => 2000,
                            'tweet_count' => 100,
                            'listed_count' => 50,
                            'following_count' => 2000,
                        ],
                    ],
                ]),
                json_encode([
                    'data' => [
                        'id' => self::ACCOUNT2,
                        'username' => 'user2',
                        'public_metrics' => [
                            'followers_count' => 4000,
                            'tweet_count' => 100,
                            'listed_count' => 50,
                            'following_count' => 4000,
                        ],
                    ],
                ])
            );

        $updateFollowersCommand = new UpdateFollowersCommand(
            $this->entityManager,
            $twitterClient,
            [self::ACCOUNT1, self::ACCOUNT2],
            date_create('2022-01-01'),
        );

        $updateFollowersCommand->execute();

        $this->assertDatabaseHasEntry(
            TwitterAccount::class,
            [
                'twitterAccountId' => self::ACCOUNT1,
                'username' => 'user1',
                'followerCount' => 2000,
                'followersPerWeek' => 19,
            ]
        );

        $this->assertDatabaseHasEntry(
            TwitterAccount::class,
            [
                'twitterAccountId' => self::ACCOUNT2,
                'username' => 'user2',
                'followerCount' => 4000,
                'followersPerWeek' => 38,
            ]
        );
    }
}

