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
        $this->request = Mockery::mock('\Omnipay\Paymongo\Message\AbstractRequest')->makePartial();
        $this->request->initialize();
    }

    public function testCardReference()
    {
        $this->assertSame($this->request, $this->request->setCardReference('abc123'));
        $this->assertSame('abc123', $this->request->getCardReference());
    }

    public function testCardToken()
    {
        $this->assertSame($this->request, $this->request->setToken('abc123'));
        $this->assertSame('abc123', $this->request->getToken());
    }

    public function testSource()
    {
        $this->assertSame($this->request, $this->request->setSource('abc123'));
        $this->assertSame('abc123', $this->request->getSource());
    }

    public function testMetadata()
    {
        $this->assertSame($this->request, $this->request->setMetadata('abc123'));
        $this->assertSame('abc123', $this->request->getMetadata());
    }
}
