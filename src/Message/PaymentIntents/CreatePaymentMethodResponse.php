<?php

/**
 * Paymongo Payment Intents Response.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\PaymentIntents\Response as BaseResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymongo Payment Intents Response.
 *
 * This is the response class for all payment intents related responses.
 *
 * @see \Omnipay\Paymongo\PaymentIntentsGateway
 */
class CreatePaymentMethodResponse extends BaseResponse implements RedirectResponseInterface
{

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        if (isset($this->data['data']['type']) && 'payment_method' === $this->data['data']['type']) {
            return $this->data['data']['id'] ? true : false;
        }

        return parent::isSuccessful();
    }

    /**
     * Get the payment intent reference.
     *
     * @return string|null
     */
    public function getPaymentIntentReference()
    {
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->data['data']['id'];
        }

        return null;
    }
}
