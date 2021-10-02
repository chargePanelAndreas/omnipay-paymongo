<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\Response;
use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new PurchaseResponse($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pay_eVMZ4LLX15WwAhF7XqRd5vc5', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseSuccessWithRedirect()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseRedirectSuccess.txt');
        $response = new PurchaseResponse($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertNotNull($response->getRedirectUrl());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseError()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseError.txt');
        $response = new PurchaseResponse($this->getMockRequest(), json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
