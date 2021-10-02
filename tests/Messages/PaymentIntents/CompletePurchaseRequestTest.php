<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    /**
     * @var CompletePurchaseRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testGetDataWithPaymentIntentIdFromRequest()
    {
        $this->getHttpRequest()->request->add([
            'paymentIntentId' => 'pi_sHYTVVRaPeAU9L3s7gNj15Go'
        ]);
        $this->request->getData();

        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $this->request->getPaymentIntentId());
    }

    public function testGetDataWithPaymentIntentIdFromParameter()
    {
        $this->request->setPaymentIntentId('pi_sHYTVVRaPeAU9L3s7gNj15Go');
        $this->request->getData();

        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $this->request->getPaymentIntentId());
    }

    public function testGetDataWithErrorPaymentIntentId()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The paymentIntentId parameter is required');
        $this->request->getData();
    }

    public function testAmountIsRequired()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The amount parameter is required');
        $this->request->setAmount(null);
        $this->request->getData();
    }

    public function testTransactionIdIsRequired()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The transactionId parameter is required');
        $this->request->setTransactionId(null);
        $this->request->getData();
    }

    public function testEndpoint()
    {
        $this->request->setPaymentIntentId('pi_sHYTVVRaPeAU9L3s7gNj15Go');
        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_sHYTVVRaPeAU9L3s7gNj15Go', $this->request->getEndpoint());
    }

    public function testHttpMethod()
    {
        $this->assertSame('GET', $this->request->getHttpMethod());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');
        $this->request->setPaymentIntentId('pi_sHYTVVRaPeAU9L3s7gNj15Go');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pay_eVMZ4LLX15WwAhF7XqRd5vc5', $response->getTransactionReference());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getPaymentIntentReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendWithTransactionIdMismatchdError()
    {
        $this->setMockHttpResponse('PurchaseSuccessTransactionIdMissing.txt');
        $this->request->setPaymentIntentId('pi_sHYTVVRaPeAU9L3s7gNj15Go');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Unable to process. Transaction id from this payment intent does not match with the current transaction id.', $response->getMessage());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getPaymentIntentReference());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseError.txt');
        $this->request->setPaymentIntentId('pi_sHYTVVRaPeAU9L3s7gNj15Go');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
