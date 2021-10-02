<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '100.00',
                'currency' => 'PHP',
                'statementDescriptor' => 'Paymongo PH',
                'description' => 'Order #90',
                'metadata' => array(
                    'foo' => 'bar',
                ),
                'paymentMethodAllowed' => 'card',
                'requestThreeDSecure' => 'automatic',
                'paymentMethod' => 'pm_ajeDG2y6WgnrCXaamWFmPUw2',
                'returnUrl' => 'http://paymongo.local/complete',
                'transactionId' => '4000',
            )
        );
        $this->request->setApiKey('some_api');
    }

    public function testGetData()
    {
        $this->setMockHttpResponse('PurchaseAuthorizeSuccess.txt');
        $data = $this->request->getData();

        $this->assertSame('http://paymongo.local/complete?paymentIntentId=pi_sHYTVVRaPeAU9L3s7gNj15Go', $data['data']['attributes']['return_url']);
        $this->assertSame('pm_ajeDG2y6WgnrCXaamWFmPUw2', $data['data']['attributes']['payment_method']);
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $this->request->getPaymentIntentId());
    }

    public function testGetDataWithTokenAsPaymentMethod()
    {
        $this->request->setPaymentMethod(null)
            ->setToken('some_token');
        $data = $this->request->getData();

        $this->assertSame('some_token', $data['data']['attributes']['payment_method']);
    }

    public function testRequestPaymentIntentIdPaymentIntentSuccess()
    {
        $this->setMockHttpResponse('PurchaseAuthorizeSuccess.txt');
        $this->request->requestPaymentIntentId();

        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $this->request->getPaymentIntentId());
    }

    public function testRequestPaymentIntentIdPaymentIntentError()
    {
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->expectExceptionMessage("Unable to create payment intent. Error: amount is required.");
        $this->setMockHttpResponse('PurchaseAuthorizeError.txt');

        $this->request->requestPaymentIntentId();
    }

    public function testEndpoint()
    {
        $this->setMockHttpResponse('PurchaseAuthorizeSuccess.txt');
        $this->request->requestPaymentIntentId();
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_sHYTVVRaPeAU9L3s7gNj15Go/attach', $this->request->getEndpoint());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseAuthorizeSuccess.txt');
        $this->request->requestPaymentIntentId();

        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pay_eVMZ4LLX15WwAhF7XqRd5vc5', $response->getTransactionReference());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getPaymentIntentReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendSuccessWithRedirect()
    {
        $this->setMockHttpResponse('PurchaseAuthorizeSuccess.txt');
        $this->request->requestPaymentIntentId();

        $this->setMockHttpResponse('PurchaseRedirectSuccess.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('https://test-sources.paymongo.com/sources?id=src_QpqBxJjtgXsqxRzxr7w6Zjef', $response->getRedirectUrl());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getPaymentIntentReference());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseAuthorizeSuccess.txt');
        $this->request->requestPaymentIntentId();

        $this->setMockHttpResponse('PurchaseError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
