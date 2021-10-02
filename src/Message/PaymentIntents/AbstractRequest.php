<?php

/**
 * Paymongo Abstract Request.
 */

namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\PaymentIntents\Response;

/**
 * Paymongo Payment Intent Abstract Request.
 *
 * This is the parent class for all Paymongo payment intent requests.
 * It adds just a getter and setter.
 *
 * @see \Omnipay\Paymongo\PaymentIntentsGateway
 * @see \Omnipay\Paymongo\Message\AbstractRequest
 * @link https://paymongo.com/docs/api/payment_intents
 */
abstract class AbstractRequest extends \Omnipay\Paymongo\Message\AbstractRequest
{
    /**
     * @param string $value
     *
     * @return \Omnipay\Paymongo\Message\PaymentIntents\AbstractRequest provides a fluent interface.
     */
    public function setPaymentIntentId($value)
    {
        return $this->setParameter('paymentIntentId', $value);
    }

    /**
     * @return mixed
     */
    public function getPaymentIntentId()
    {
        return $this->getParameter('paymentIntentId');
    }

    /**
     * @param string $value
     *
     * @return \Omnipay\Paymongo\Message\PaymentIntents\AbstractRequest provides a fluent interface.
     */
    public function setClientKey($value)
    {
        return $this->setParameter('clientKey', $value);
    }

    /**
     * @return mixed
     */
    public function getClientKey()
    {
        return $this->getParameter('clientKey');
    }

    /**
     * If there's a reference to a payment method, return that instead.
     *
     * @inheritdoc
     */
    public function getCardReference()
    {
        if ($paymentMethod = $this->getPaymentMethod()) {
            return $paymentMethod;
        }

        return parent::getCardReference();
    }

    /**
     * Actually, set the payment method, which is the preferred API.
     *
     * @inheritdoc
     */
    public function setCardReference($reference)
    {
        $this->setPaymentMethod($reference);
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }
}
