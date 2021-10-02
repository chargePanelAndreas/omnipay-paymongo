<?php

/**
 * Paymongo Attach Payment Method Request.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\PaymentIntents\Response;

/**
 * Paymongo Attach Payment Method Request.
 *
 * This request is used to attach an existing payment method to an existing customer.
 * The `attachCard` method *will not work* on the Charge gateway.
 *
 * ### Example
 *
 * This example assumes that you have already created both a customer and a
 * payment method and that the data is stored in $customerId and $paymentMethodId, respectively.
 *
 * <code>
 *   // Do an attach card transaction on the gateway
 *   $response = $gateway->attachCard(array(
 *       'paymentMethod'     => $paymentMethodId,
 *       'customerReference' => $customerId,
 *   ))->send();
 *   if ($response->isSuccessful()) {
 *       echo "Gateway attachCard was successful.\n";
 *       // Find the card ID
 *       $methodId = $response->getCardReference();
 *       echo "Method ID = " . $methodId . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\Paymongo\Message\PaymentIntents\CreatePaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\CreateCustomerRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\DetachPaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\UpdatePaymentMethodRequest
 * @link https://paymongo.com/docs/api/payment_methods/attach
 * @method Response send()
 */
class AttachPaymentMethodRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [];

        $this->validate('apiKey');
        $this->validate('paymentIntentId');
        $this->validate('paymentMethod');

        $data['return_url'] = $this->getReturnUrl();
        $data['payment_method'] = $this->getPaymentMethod();

        return [
            'data' => [
                'attributes' => $data
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/payment_intents/' . $this->getPaymentIntentId() . '/attach';
    }

}
