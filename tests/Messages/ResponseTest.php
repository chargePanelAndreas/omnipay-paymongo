<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testAuthorizeSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeSuccess.txt');
        $response = new Response($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testAuthorizeError()
    {
        $httpResponse = $this->getMockHttpResponse('AuthorizeError.txt');
        $response = new Response($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
