<?php

/**
 * Paymongo Fetch Payment Intent Request.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

/**
 * Paymongo Fetch Payment Intent Request.
 *
 *  // Check if we're good!
 *  $paymentIntent = $gateway->fetchPaymentIntent(array(
 *      'paymentIntentReference' => $paymentIntentReference,
 *  ));
 *
 *  $response = $paymentIntent->send();
 *
 *  if ($response->isSuccessful()) {
 *    // All done. Rejoice.
 *  }
 *
 * @link https://paymongo.com/docs/api/payment_intents/retrieve
 */
class FetchPaymentRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->validate('paymentIntentId');

        return [
            'clientKey' => $this->getClientKey()
        ];
    }

    /**
     * @inheritdoc
     */
    public function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint . '/payment_intents/' . $this->getPaymentIntentId();
    }
}
