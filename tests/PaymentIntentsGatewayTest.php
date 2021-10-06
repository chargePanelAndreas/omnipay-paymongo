<?php

namespace Omnipay\Paymongo;

use Omnipay\Tests\GatewayTestCase;

class PaymentIntentsGatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Paymongo\PaymentIntentsGateway
     */
    protected $gateway;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = new PaymentIntentsGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));

        $this->assertInstanceOf('\Omnipay\Paymongo\Message\PaymentIntents\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase([]);

        $this->assertInstanceOf('\Omnipay\Paymongo\Message\PaymentIntents\CompletePurchaseRequest', $request);
    }

}
