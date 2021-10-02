<?php

namespace Omnipay\Paymongo\Message;

use GuzzleHttp\Psr7\Request;
use Mockery;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /** @var Mockery\Mock|AbstractRequest */
    private $request;

    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\Paymongo\Message\Sources\AbstractRequest')->makePartial();
        $this->request->initialize();
        $this->request->setApiKey('some_api');
    }

    public function testSourceId()
    {
        $this->assertSame($this->request, $this->request->setSourceId('abc123'));
        $this->assertSame('abc123', $this->request->getSourceId());
    }

}
