<?php

/**
 * Paymongo Payment Intents Gateway.
 */

namespace Omnipay\Paymongo;

use Omnipay\Paymongo\Message\Sources\CompletePurchaseRequest;

/**
 * Paymongo Payment Intents Gateway.
 *
 * @see \Omnipay\Paymongo\AbstractGateway
 * @see \Omnipay\Paymongo\Message\AbstractRequest
 *
 * @link https://paymongo.com/docs/api
 *
 */
class SourceGateway extends AbstractGateway
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
        return $this->createRequest('\Omnipay\Paymongo\Message\Sources\PurchaseRequest', $parameters);
    }

    /**
     * @inheritdoc
     *
     * In reality, we're creating the payment from source id stored from temporary session.
     * This method exists as an alias to in line with how Omnipay interfaces define things.
     *
     * @return \Omnipay\Paymongo\Message\Sources\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\Sources\CompletePurchaseRequest', $parameters);
    }

}
