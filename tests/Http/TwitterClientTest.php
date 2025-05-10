<?php

namespace App\Tests\Http;

use App\Http\TwitterClient;
use PHPUnit\Framework\TestCase;
use App\Http\ApplicationClientInterface;

class TwitterClientTest extends TestCase
{
    /**
     * @test
     */
    public function getUserById_return_proper_formatted_data()
    {
        $accountId = 123456;
        $mockedResponse = json_encode([
            'data' => [
                'id' => $accountId,
                'name' => 'Test User',
                'username' => 'testuser',
                'public_metrics' => [
                    'followers_count' => 1000,
                    'following_count' => 500,
                    'tweet_count' => 200,
                    'listed_count' => 10,
                ],
            ],
        ]);

        $mockedClient = $this->createMock(ApplicationClientInterface::class);

        $mockedClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains($accountId))
            ->willReturn($mockedResponse)
            ;

        $twitterClient = new TwitterClient($mockedClient);
        $result = $twitterClient->getUserById($accountId);

        $this->assertEquals($accountId, $result['id']);
        $this->assertEquals('Test User', $result['name']);
        $this->assertEquals('testuser', $result['username']);
        $this->assertEquals(1000, $result['public_metrics']['followers_count']);
    }
}