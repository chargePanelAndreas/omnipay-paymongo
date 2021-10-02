<?php

/**
 * Paymongo Payment Intents Cancel Request.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\Response;

/**
 * Paymongo Cancel Payment Intent Request.
 *
 * <code>
 *   $paymentIntent = $gateway->cancelPaymentIntent(array(
 *       'paymentIntentId' => $paymentIntentId,
 *   ));
 *
 *   $response = $paymentIntent->send();
 *
 *   if ($response->isCancelled()) {
 *     // All done
 *   }
 * </code>
 *
 * @link https://paymongo.com/docs/api/payment_intents/cancel
 */
class CancelPaymentIntentRequest extends AbstractRequest
{
    /**
     * @inheritdoc
     */
    public function getData()
    {
        $this->validate('apiKey');
        $this->validate('paymentIntentId');

        return [];
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/payment_intents/' . $this->getPaymentIntentId() . '/cancel';
    }

    /**
     * @param $data
     * @param $statusCode
     * @return CancelPaymentIntentResponse
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CancelPaymentIntentResponse($this, $data, $statusCode);
    }
}
