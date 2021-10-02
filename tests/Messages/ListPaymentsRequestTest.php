<?php

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Tests\TestCase;

class ListPaymentsRequestTest extends TestCase
{
    /**
     * @var CreatePaymentMethodRequest
     */
    private $request;

    public function setUp()
    {
        $this->request = new CreatePaymentMethodRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->setCard(array_merge($this->getValidCard(), [
            'email' => 'johndoe@example.com'
        ]));
    }

    public function testEndpoint()
    {
        $this->assertSame('https://api.paymongo.com/v1/payment_methods', $this->request->getEndpoint());
    }

    public function testCard()
    {
        $this->expectExceptionMessage("The card parameter is required");
        $this->expectException(\Omnipay\Common\Exception\InvalidRequestException::class);
        $this->request->setCard(null);
        $this->request->getData();
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertSame($card['number'], $data['data']['attributes']['details']['card_number']);
        $this->assertSame($card['expiryMonth'], $data['data']['attributes']['details']['exp_month']);
        $this->assertSame($card['expiryYear'], $data['data']['attributes']['details']['exp_year']);
        $this->assertSame($card['cvv'], $data['data']['attributes']['details']['cvc']);

        $this->assertSame($card['billingAddress1'], $data['data']['attributes']['billing']['address']['line1']);
        $this->assertSame($card['billingAddress2'], $data['data']['attributes']['billing']['address']['line2']);
        $this->assertSame($card['billingCity'], $data['data']['attributes']['billing']['address']['city']);
        $this->assertSame($card['billingState'], $data['data']['attributes']['billing']['address']['state']);
        $this->assertSame($card['billingPostcode'], $data['data']['attributes']['billing']['address']['postal_code']);
        $this->assertSame($card['billingCountry'], $data['data']['attributes']['billing']['address']['country']);

        $this->assertSame($card['firstName'] . ' ' . $card['lastName'], $data['data']['attributes']['billing']['name']);
        $this->assertSame(null, $data['data']['attributes']['billing']['email']);
        $this->assertSame($card['billingPhone'], $data['data']['attributes']['billing']['phone']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('CreatePaymentMethodSuccess.txt');

        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('pm_axRpEVktHVoSA5748RGBi1Ce', $response->getTransactionReference());
        $this->assertSame('pm_axRpEVktHVoSA5748RGBi1Ce', $response->getPaymentMethodReference());
        $this->assertNull($response->getMessage());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('CreatePaymentMethodError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('details.card_number format is invalid.', $response->getMessage());
    }

    public function testCardWithoutEmail()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertNull($data['data']['attributes']['billing']['email']);
    }
}
