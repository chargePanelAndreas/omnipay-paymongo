<?php

namespace Omnipay\Paymongo\Message\Sources;

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
                'description' => 'Order #4000',
            )
        );
        $this->request->setApiKey('some_api');

        // set existing session
        $session_name = $this->request->getSourceIdSessionName();
        $this->request->saveSourceIdToSession('src_XpHZz6C2wk112P9Mufguhcch');
        $this->getHttpRequest()->query->add([
            'session' => $session_name,
        ]);
    }

    public function testSessionName()
    {
        $this->assertSame($this->request, $this->request->setSessionName('sessionName'));
        $this->assertSame('sessionName', $this->request->getSessionName());
    }

    public function testData()
    {
        $data = $this->request->getData();

        $this->assertSame(10000, $data['data']['attributes']['amount']);
        $this->assertSame('Order #4000', $data['data']['attributes']['description']);
        $this->assertSame('source', $data['data']['attributes']['source']['type']);
        $this->assertSame('src_XpHZz6C2wk112P9Mufguhcch', $data['data']['attributes']['source']['id']);
        $this->assertSame('PHP', $data['data']['attributes']['currency']);
        $this->assertSame('Paymongo PH', $data['data']['attributes']['statement_descriptor']);
    }

    public function testSessionQueryRequired()
    {
        $this->getHttpRequest()->query->remove('session');
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage("The sessionName parameter or session query is required or invalid.");

        $this->request->getData();
    }

    public function testSourceIdRequired()
    {
        $this->request->saveSourceIdToSession('');
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage("Unable to load Source id from session.");

        $this->request->getData();
    }

    public function testAmountIsRequired()
    {
        $this->expectException(InvalidRequestException::class);
        $this->expectExceptionMessage('The amount parameter is required');
        $this->request->setAmount(null);
        $this->request->getData();
    }

    public function testEndpoint()
    {
        $this->assertSame('https://api.paymongo.com/v1/payments', $this->request->getEndpoint());
    }

    public function testHttpMethod()
    {
        $this->assertSame('POST', $this->request->getHttpMethod());
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CompletePurchaseSourceSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pay_4uHme43oZQ2LLHpFx3u9tRra', $response->getTransactionReference());
        $this->assertSame(floatval('100.00'), floatval($response->getAmount()));
        $this->assertNull($response->getMessage());
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('CompletePurchaseSourceError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}
