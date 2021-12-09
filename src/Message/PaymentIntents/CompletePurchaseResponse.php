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
class CompletePurchaseResponse extends PurchaseResponse
{

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        // we need to verify if the transaction id
        if (!$this->isTransactionIdMatches()) {
            return false;
        }

        return parent::isSuccessful();
    }

    /**
     * Since Paymongo might already had charged the card before we the client
     * is redirected back to the merchant site, we need to confirm if the passed
     * transaction id is the same to the transaction id returned from FetchPaymentIntent
     *
     * @return bool
     */
    public function isTransactionIdMatches()
    {
        return $this->getTransactionId() == $this->request->getTransactionId();
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
        if (!$this->isTransactionIdMatches()) {
            return 'Unable to process. Transaction id from this payment intent does not match with the current transaction id.';
        }

        return parent::getMessage();
    }

}
