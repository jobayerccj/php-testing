<?php

namespace App\Tests\Integration\Http;

use PHPUnit\Framework\TestCase;
use App\Http\SymfonyHttpApplicationClient;
use Symfony\Component\HttpClient\HttpClient;

/**
 * @group internal
 */
class SymfonyHttpApplicationClientTest extends TestCase
{
    private const PHPUNIT_ID = 19057969;
    private const BASE_URL = 'https://api.twitter.com/2/';

    /** @test */
    public function get_throws_authentication_exception()
    {
        $httpClient = HttpClient::create([
            'headers' => ['Authorization' => 'fakeToekn']
        ]);

        $symfonyHttpApplicationClient = new SymfonyHttpApplicationClient($httpClient);
        $url = self::BASE_URL . 'users/' . self::PHPUNIT_ID . '?user.fields=public_metrics';

        $this->expectException(\App\Http\ApplicationClientException::class);
        $this->expectExceptionCode(401);

        $symfonyHttpApplicationClient->get($url);
    }
}
