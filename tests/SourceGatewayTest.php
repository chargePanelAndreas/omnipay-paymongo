<?php

namespace Omnipay\Paymongo;

use Omnipay\Paymongo\Message\Sources\PurchaseRequest;
use Omnipay\Tests\GatewayTestCase;

class SourceGatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Paymongo\SourceGateway
     */
    protected $gateway;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = new SourceGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase([]);

        $this->assertInstanceOf('\Omnipay\Paymongo\Message\Sources\CompletePurchaseRequest', $request);
    }

}
