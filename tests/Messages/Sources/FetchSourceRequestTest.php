<?php

namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Tests\TestCase;

class FetchPaymentRequestTest extends TestCase
{
    /**
     * @var FetchSourceRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new FetchSourceRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setSourceId('src_AmKhe8cFaer2SRWLz44xBmsD');
        $this->request->setApiKey('some_api');
    }

    public function testEndpoint()
    {
        $this->assertSame('https://api.paymongo.com/v1/sources/src_AmKhe8cFaer2SRWLz44xBmsD', $this->request->getEndpoint());
    }

    public function testData()
    {
        $this->assertEmpty($this->request->getData());
    }

    public function testDataRequiredSourceId()
    {
        $this->expectExceptionMessage("The sourceId parameter is required");
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->request->setSourceId(null);
        $this->request->getData();
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchSourceSuccess.txt');

        $this->request->setSourceId('src_AmKhe8cFaer2SRWLz44xBmsD');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertSame('src_AmKhe8cFaer2SRWLz44xBmsD', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchSourceError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('No such source with id src_AmKhe8cFaer2SRWLz44xBmsDs.', $response->getMessage());
    }
}
