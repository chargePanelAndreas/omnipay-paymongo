<?php

/**
 * Paymongo Payment Intents Gateway.
 */

namespace Omnipay\Paymongo;

/**
 * Paymongo Payment Intents Gateway.
 *
 * @see \Omnipay\Paymongo\AbstractGateway
 * @see \Omnipay\Paymongo\Message\AbstractRequest
 *
 * @link https://paymongo.com/docs/api
 *
 */
class PaymentIntentsGateway extends AbstractGateway
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Paymongo Payment Intents';
    }

    /**
     * @inheritdoc
     *
     * @return \Omnipay\Paymongo\Message\PaymentIntents\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\PaymentIntents\PurchaseRequest', $parameters);
    }

    /**
     * @inheritdoc
     *
     * In reality, we're confirming the payment intent.
     * This method exists as an alias to in line with how Omnipay interfaces define things.
     *
     * @return \Omnipay\Paymongo\Message\PaymentIntents\ConfirmPaymentIntentRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\PaymentIntents\CompletePurchaseRequest', $parameters);
    }


}
