<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use GuzzleHttp\Psr7\Request;
use Mockery;
use Omnipay\Paymongo\Message\PaymentIntents\AbstractRequest;
use Omnipay\Tests\TestCase;

class AbstractRequestTest extends TestCase
{
    /** @var AbstractRequest */
    protected $request;

    public function setUp()
    {
        $this->request = Mockery::mock('\Omnipay\Paymongo\Message\PaymentIntents\AbstractRequest')->makePartial();
        $this->request->initialize();
        $this->request->setApiKey('some_api');
    }

    public function testPaymentIntentReference()
    {
        $this->assertSame($this->request, $this->request->setPaymentIntentId('abc123'));
        $this->assertSame('abc123', $this->request->getPaymentIntentId());
    }

    public function testClientKey()
    {
        $this->assertSame($this->request, $this->request->setClientKey('abc123'));
        $this->assertSame('abc123', $this->request->getClientKey());
    }

    public function testPaymentMethodAlternatives()
    {
        $this->request->setCardReference('card_some_card');
        $this->assertSame('card_some_card', $this->request->getCardReference());
        $this->assertSame('card_some_card', $this->request->getPaymentMethod());
    }
}


