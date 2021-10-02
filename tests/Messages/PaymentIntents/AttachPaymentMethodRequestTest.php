<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class AttachPaymentMethodRequestTest extends TestCase
{
    /**
     * @var AttachPaymentMethodRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new AttachPaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setApiKey('some_api');
        $this->request->setPaymentIntentId('pi_u9zxu56zCZecBPLQRMd1Qmhd');
        $this->request->setPaymentMethod('pm_ajeDG2y6WgnrCXaamWFmPUw2');
        $this->request->setReturnUrl('someReturnUrl');
    }

    public function testEndpoint()
    {
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_u9zxu56zCZecBPLQRMd1Qmhd/attach', $this->request->getEndpoint());
    }

    public function testMissingPaymentMethod()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage("The paymentMethod parameter is required");
        $this->request->setPaymentMethod(null);
        $this->request->getData();
    }

    public function testData()
    {
        $data = $this->request->getData();

        $this->assertSame('pm_ajeDG2y6WgnrCXaamWFmPUw2', $data['data']['attributes']['payment_method']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('AttachPaymentMethodSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pi_u9zxu56zCZecBPLQRMd1Qmhd', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendRedirectSuccess()
    {
        $this->setMockHttpResponse('AttachPaymentMethodRedirectSuccess.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('pi_EjCvhNSPu8v16TKVe28F1iFy', $response->getTransactionReference());
        $this->assertSame('https://test-sources.paymongo.com/sources?id=src_QpqBxJjtgXsqxRzxr7w6Zjef', $response->getRedirectUrl());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('AttachPaymentMethodError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Payment intent with id pi_u9zxu56zCZecBPLQRMd1Qmhd has already succeeded.', $response->getMessage());
    }
}
