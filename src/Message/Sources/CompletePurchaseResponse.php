<?php

/**
 * Paymongo Payment Intents Response.
 */
namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymongo Payment Intents Response.
 *
 * This is the response class for all payment intents related responses.
 *
 * @see \Omnipay\Paymongo\PaymentIntentsGateway
 */
class CompletePurchaseResponse extends Response
{

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isPaid();
    }

    /**
     * Get the status of a payment intents response.
     *
     * @return string|null
     */
    public function getStatus()
    {
        if (isset($this->data['data']) && 'payment' === $this->data['data']['type']) {
            return $this->data['data']['attributes']['status'];
        }

        return null;
    }

    /**
     * Get the status of a payment intents response.
     *
     * @return string|null
     */
    public function getAmount()
    {
        if (isset($this->data['data']) && 'payment' === $this->data['data']['type']) {
            return ($this->data['data']['attributes']['amount'] / 100);
        }

        return null;
    }
}
