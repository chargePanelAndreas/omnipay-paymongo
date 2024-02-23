<?php

/**
 * Stripe Payment Intents Gateway.
 */

namespace Omnipay\Paymongo;

/**
 * Stripe Payment Intents Gateway.
 *
 * @see  \Omnipay\Stripe\AbstractGateway
 * @see  \Omnipay\Stripe\Message\AbstractRequest
 * @link https://stripe.com/docs/api
 * @method \Omnipay\Common\Message\RequestInterface refund(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface void(array $options = array())
 */
class CheckoutGateway extends AbstractGateway
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Paymongo Checkout';
    }

    /**
     * @inheritdoc
     * @return \Omnipay\Paymongo\Message\Checkout\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\Checkout\PurchaseRequest', $parameters);
    }


    public function acceptNotification()
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\Checkout\AcceptNotification', []);
    }

}
