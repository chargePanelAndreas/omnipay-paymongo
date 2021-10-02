<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Tests\TestCase;

class FetchPaymentIntentRequestTest extends TestCase
{
    /**
     * @var FetchPaymentIntentRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new FetchPaymentIntentRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'paymentIntentId' => 'pi_Nxm1QTysXsttvfNNy24DK9Xa',
                'clientKey' => 'pi_Nxm1QTysXsttvfNNy24DK9Xa_client_M1T7WPcFLvQu912wHmWHfdbA',
            )
        );
        $this->request->setApiKey('some_api');
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('pi_Nxm1QTysXsttvfNNy24DK9Xa_client_M1T7WPcFLvQu912wHmWHfdbA', $data['clientKey']);
    }

    public function testEndPoint()
    {
        $endpoint = $this->request->getEndpoint();

        $this->assertSame('https://api.paymongo.com/v1/payment_intents/pi_Nxm1QTysXsttvfNNy24DK9Xa', $endpoint);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchSuccess.txt');
        /** @var Response $response */
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('FetchError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
