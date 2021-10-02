<?php

namespace Omnipay\Paymongo\Message;

use Omnipay\Tests\TestCase;

class CreatePaymentRequestTest extends TestCase
{
    /**
     * @var CreatePaymentRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CreatePaymentRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '100.00',
                'currency' => 'PHP',
                'statementDescriptor' => 'Paymongo PH',
                'description' => 'Order #90',
                'paymentMethodAllowed' => 'card',
                'sourceId' => 'tok_X925Gje9FzRxfZCiBzNaSCbE',
                'sourceType' => 'token',
            )
        );
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(10000, $data['data']['attributes']['amount']);
        $this->assertSame('PHP', $data['data']['attributes']['currency']);
        $this->assertSame('Paymongo PH', $data['data']['attributes']['statement_descriptor']);
        $this->assertSame('Order #90', $data['data']['attributes']['description']);
        $this->assertSame('tok_X925Gje9FzRxfZCiBzNaSCbE', $data['data']['attributes']['source']['id']);
        $this->assertSame('token', $data['data']['attributes']['source']['type']);
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
