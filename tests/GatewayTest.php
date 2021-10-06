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

}
