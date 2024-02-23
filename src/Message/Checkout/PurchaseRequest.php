<?php

/**
 * Stripe Checkout Session Request.
 */

namespace Omnipay\Paymongo\Message\Checkout;

/**
 * Stripe Checkout Session Request
 *
 * @see \Omnipay\Stripe\Gateway
 * @link https://stripe.com/docs/api/checkout/sessions
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * Set the success url
     *
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setSuccessUrl($value)
    {
        return $this->setParameter('success_url', $value);
    }

    /**
     * Get the success url
     *
     * @return string
     */
    public function getSuccessUrl()
    {
        return $this->getParameter('success_url');
    }
    /**
     * Set the cancel url
     *
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setCancelUrl($value)
    {
        return $this->setParameter('cancel_url', $value);
    }

    /**
     * Get the success url
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getParameter('cancel_url');
    }

    /**
     * Set the payment method types accepted url
     *
     * @param array $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setPaymentMethodTypes($value)
    {
        return $this->setParameter('payment_method_types', $value);
    }

    /**
     * Get the success url
     *
     * @return string
     */
    public function getPaymentMethodTypes()
    {
        return $this->getParameter('payment_method_types');
    }


    /**
     * Set the payment method types accepted url
     *
     * @param array $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setLineItems($value)
    {
        return $this->setParameter('line_items', $value);
    }

    /**
     * Get the success url
     *
     * @return array
     */
    public function getLineItems()
    {
        return $this->getParameter('line_items');
    }

    /**
     * Set Billing details
     *
     * @param array $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setBilling($value)
    {
        return $this->setParameter('billing', $value);
    }

    /**
     * Billing details
     *
     * @return array
     */
    public function getBilling()
    {
        return $this->getParameter('billing');
    }

    /**
     * Set the payment method types accepted url
     *
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setClientReferenceId($value)
    {
        return $this->setParameter('client_reference_id', $value);
    }

    /**
     * Get the success url
     *
     * @return string
     */
    public function getClientReferenceId()
    {
        return $this->getParameter('client_reference_id');
    }

    /**
     * Set the customer_creation parameter
     *
     * @param string $value
     *
     * @return \Omnipay\Common\Message\AbstractRequest|PurchaseRequest
     */
    public function setCustomerCreation($value)
    {
        return $this->setParameter('customer_creation', $value);
    }

    /**
     * Get the customer_creation parameter
     *
     * @return string
     */
    public function getCustomerCreation()
    {
        return $this->getParameter('customer_creation');
    }

    public function getData()
    {
        $data = array(
            'client_reference_id' => $this->getClientReferenceId(),
            'reference_number' => $this->getTransactionId(),
            'success_url' => $this->getSuccessUrl(),
            'cancel_url' => $this->getCancelUrl(),
            'payment_method_types' => $this->getPaymentMethodTypes(),
            'line_items' => $this->getLineItems(),
            'billing' => $this->getBilling(),
        );

        $this->validate('apiKey');
        $this->validate('line_items');

        // return $data;
        return [
            'data' => [
                'attributes' => $data
            ]
        ];
    }

    public function getEndpoint()
    {
        return $this->endpoint.'/checkout_sessions';
    }
}
