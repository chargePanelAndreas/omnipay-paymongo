<?php

/**
 * Paymongo Fetch Payment Intent Request.
 */
namespace Omnipay\Paymongo\Message\Sources;

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
class FetchSourceRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->validate('sourceId');

        return [];
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
        return $this->endpoint . '/sources/' . $this->getSourceId();
    }
}
