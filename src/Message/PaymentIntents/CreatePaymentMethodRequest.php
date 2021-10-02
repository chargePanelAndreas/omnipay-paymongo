<?php

/**
 * Paymongo Create Payment Method Request.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\Response;

/**
 * Paymongo Create Payment Method Request.
 *
 * Paymongo payment methods differs a little bit from creating a card.
 * When using the Payment Intent API, it is mandatory to use a payment method,
 * so a lot of times you'll be creating a payment method without an assigned customer.
 *
 * Another difference is that it's impossible to create a payment method and assign
 * it to a user in a single request. Instead, you create a payment method and then
 * attach it.
 *
 * ### Example
 *
 * <code>
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   $new_card = new CreditCard([
 *       'firstName'     => 'Example',
 *       'lastName'      => 'Customer',
 *       'number'        => '5555555555554444',
 *       'expiryMonth'   => '01',
 *       'expiryYear'    => '2020',
 *       'cvv'           => '456',
 *       'email'             => 'customer@example.com',
 *       'billingAddress1'   => '1 Lower Creek Road',
 *       'billingCountry'    => 'AU',
 *       'billingCity'       => 'Upper Swan',
 *       'billingPostcode'   => '6999',
 *       'billingState'      => 'WA',
 *   ]);
 *
 *   // Do a create card transaction on the gateway
 *   $response = $gateway->createCard(['card' => $new_card])->send();
 *   if ($response->isSuccessful()) {
 *       echo "Gateway createCard was successful.\n";
 *       // Find the card ID
 *       $method_id = $response->getCardReference();
 *       echo "Method ID = " . $method_id . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\Paymongo\Message\PaymentIntents\AttachPaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\DetachPaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\UpdatePaymentMethodRequest
 * @link https://paymongo.com/docs/api/payment_methods/create
 * @method CreatePaymentMethodResponse send()
 */
class CreatePaymentMethodRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = [];
        $this->validate('card');

        return [
            'data' => [
                'attributes' => $this->getCardData()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/payment_methods';
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CreatePaymentMethodResponse($this, $data, $statusCode);
    }
}
