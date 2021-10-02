<?php

namespace Omnipay\Paymongo;

use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    /**
     * @var \Omnipay\Paymongo\Gateway
     */
    protected $gateway;


    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

    }

    /** @test */
    public function configuration()
    {
        $request = $this->gateway->configuration();

        $this->assertInstanceOf('Omnipay\Paymongo\Message\ConfigurationRequest', $request);
    }

    /** @test */
    public function configurationRequest()
    {
        $this->setMockHttpResponse('ConfigurationSuccess.txt');

        $response = $this->gateway->configuration()->send();
        $contents = $response->getData();

        $expected = [
            "type" => "PAY_BY_INSTALLMENT",
            "description" => "Pay over time",
            "minimumAmount" => [
              "amount" => "0.00",
              "currency" => "NZD"
            ],
            "maximumAmount" => [
              "amount" => "1000.00",
              "currency" => "NZD"
            ]
        ];

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals($expected, $contents[0]);
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => '10.00'));
        $this->assertInstanceOf('Omnipay\Paymongo\Message\PurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => '10.00'));
        $this->assertInstanceOf('Omnipay\Paymongo\Message\CompletePurchaseRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
    }

    public function testRefund()
    {
        $options = array('amount' => '10.00', 'currency' => 'NZD');
        $request = $this->gateway->refund($options);

        $this->assertInstanceOf('Omnipay\Paymongo\Message\RefundRequest', $request);
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('NZD', $request->getCurrency());
    }
}
