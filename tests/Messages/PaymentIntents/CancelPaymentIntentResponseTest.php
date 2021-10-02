<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\Response;
use Omnipay\Tests\TestCase;

class CancelPaymentIntentResponseTest extends TestCase
{
    public function testCancelSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CancelPaymentIntentSuccess.txt');
        $response = new CancelPaymentIntentResponse($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testCancelError()
    {
        $httpResponse = $this->getMockHttpResponse('CancelPaymentIntentError.txt');
        $response = new CancelPaymentIntentResponse($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }

}
