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
class CancelPaymentIntentResponse extends Response
{

    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isCancelled();
    }


}
