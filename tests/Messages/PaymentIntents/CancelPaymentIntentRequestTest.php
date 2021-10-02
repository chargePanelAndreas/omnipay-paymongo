<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class CancelPaymentIntentRequestTest extends TestCase
{
    /**
     * @var CancelPaymentIntentRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CancelPaymentIntentRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setPaymentIntentId('pi_Nxm1QTysXsttvfNNy24DK9Xa');
        $this->request->setApiKey('some_api');
    }

    public function testEndpoint()
    {
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_Nxm1QTysXsttvfNNy24DK9Xa/cancel', $this->request->getEndpoint());
    }

    public function testPaymentIntent()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage("The paymentIntentId parameter is required");
        $this->request->setPaymentIntentId(null);
        $this->request->getData();
    }

    public function testData()
    {
        $this->assertEmpty($this->request->getData());
    }

    public function testCancelSuccess()
    {
        $this->setMockHttpResponse('CancelPaymentIntentSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isCancelled());
        $this->asserttrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());

        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getTransactionReference());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go_client_9xcwSuxAVCmftufn7hhWULoG', $response->getClientKey());
        $this->assertNull($response->getMessage());
    }

    public function testCancelFailure()
    {
        $this->setMockHttpResponse('CancelPaymentIntentError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isCancelled());
        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Payment intent with id pi_sHYTVVRaPeAU9L3s7gNj15Go has already been cancelled.', $response->getMessage());
    }
}
