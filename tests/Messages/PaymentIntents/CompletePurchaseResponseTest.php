<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\Response;
use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $request = $this->getMockRequest();
        $request->shouldReceive('getTransactionId')
            ->andReturn(4000);

        $response = new CompletePurchaseResponse($request, json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pay_eVMZ4LLX15WwAhF7XqRd5vc5', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchaseError()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseError.txt');
        $request = $this->getMockRequest();
        $request->shouldReceive('getTransactionId')
            ->andReturn(4000);

        $response = new CompletePurchaseResponse($request, json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }

    public function testSendWithTransactionIdMismatchdError()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccessTransactionIdMissing.txt');
        $request = $this->getMockRequest();
        $request->shouldReceive('getTransactionId')
            ->andReturn(4000);

        $response = new CompletePurchaseResponse($request, json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Unable to process. Transaction id from this payment intent does not match with the current transaction id.', $response->getMessage());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getPaymentIntentReference());
    }
}
