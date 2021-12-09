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


    /**
     * Get the first error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (!$this->isSuccessful() && $this->getStatus() == 'awaiting_payment_method') {
            if (isset($this->data['data']['attributes']['last_payment_error']) && $this->data['data']['attributes']['last_payment_error']) {

                if (is_string($this->data['data']['attributes']['last_payment_error'])) {
                    return $this->data['data']['attributes']['last_payment_error'];
                } else {
                    if (isset($this->data['data']['attributes']['last_payment_error']['failed_message']) && $this->data['data']['attributes']['last_payment_error']['failed_message']) {
                        return $this->data['data']['attributes']['last_payment_error']['failed_message'];
                    }
                }
            }

            return 'Unable to process. Payment not succeeded. Please try again.';
        }

        if (!$this->isSuccessful() && isset($this->data['errors'][0]['detail'])) {
            return $this->data['errors'][0]['detail'];
        }

        return parent::getMessage();
    }
}
