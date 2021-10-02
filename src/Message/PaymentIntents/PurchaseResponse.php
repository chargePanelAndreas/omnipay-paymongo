<?php

/**
 * Paymongo Payment Intents Response.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymongo Payment Intents Response.
 *
 * This is the response class for all payment intents related responses.
 *
 * @see \Omnipay\Paymongo\PaymentIntentsGateway
 */
class PurchaseResponse extends Response
{

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isSucceeded();
    }

    /**
     * @inheritdoc
     */
    public function getTransactionReference()
    {
        if (isset($this->data['data']['attributes']['payments']) && count($this->data['data']['attributes']['payments'])) {
            $payments = $this->data['data']['attributes']['payments'];
            return array_pop($payments)['id'];
        }
    }

    /**
     * Get the status of a payment intents response.
     *
     * @return string|null
     */
    public function getStatus()
    {
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->data['data']['attributes']['status'];
        }

        return null;
    }

}
