<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Tests\TestCase;

class AuthorizeRequestTest extends TestCase
{
    /**
     * @var AuthorizeRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
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
                'transactionId' => '200389',
            )
        );
        $this->request->setApiKey('some_api');
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(10000, $data['data']['attributes']['amount']);
        $this->assertSame('PHP', $data['data']['attributes']['currency']);
        $this->assertSame('Paymongo PH', $data['data']['attributes']['statement_descriptor']);
        $this->assertSame('Order #90', $data['data']['attributes']['description']);
        $this->assertSame(array('foo' => 'bar', 'transactionId' => '200389'), $data['data']['attributes']['metadata']);
        $this->assertSame('card', $data['data']['attributes']['payment_method_allowed'][0]);
        $this->assertSame('automatic', $data['data']['attributes']['payment_method_options']['card']['request_three_d_secure']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');
        /** @var Response $response */
        $response = $this->request->send();

        $this->assertNotEmpty($response->getStatus());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pi_sHYTVVRaPeAU9L3s7gNj15Go', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('AuthorizeError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
