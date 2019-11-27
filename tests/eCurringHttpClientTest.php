<?php

namespace Mooore\eCurring;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class eCurringHttpClientTest extends TestCase
{
    /**
     * @var ClientInterface|MockObject
     */
    private $httpClient;
    /**
     * @var eCurringHttpClient
     */
    private $eCurringApiClient;

    /**
     * @return void
     * @throws ReflectionException
     */
    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(ClientInterface::class);
        $this->eCurringApiClient = new eCurringHttpClient($this->httpClient);

        $this->eCurringApiClient->setApiKey('a_very_interesting_api_key');
    }

    public function testHttpCallReturnsBodyAsObject()
    {
        $response = new Response(200, [], '{"resource": "customer"}');

        $this->httpClient->expects($this->once())
            ->method('send')
            ->willReturn($response);

        $parsedResponse = $this->eCurringApiClient->performHttpCall('GET', '');

        $this->assertEquals(
            (object)['resource' => 'customer'],
            $parsedResponse
        );
    }
}
