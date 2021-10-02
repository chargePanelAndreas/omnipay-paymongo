<?php

/**
 * Paymongo Payment Intents Response.
 */
namespace Omnipay\Paymongo\Message\PaymentIntents;

use Omnipay\Paymongo\Message\Response as BaseResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymongo Payment Intents Response.
 *
 * This is the response class for all payment intents related responses.
 *
 * @see \Omnipay\Paymongo\PaymentIntentsGateway
 * @property AbstractRequest $request
 */
class Response extends BaseResponse implements RedirectResponseInterface
{

    /**
     * Get
     *
     * @return string|null
     */
    public function getClientKey()
    {
        if (isset($this->data['data']['attributes']['client_key']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->data['data']['attributes']['client_key'];
        }

        return null;
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
     * @inheritdoc
     */
    public function isCancelled()
    {
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->getStatus() === 'cancelled';
        }

        return parent::isCancelled();
    }

    /**
     * @inheritdoc
     */
    public function isProcessing()
    {
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->getStatus() === 'processing';
        }

        return parent::isCancelled();
    }

    /**
     * @inheritdoc
     */
    public function isSucceeded()
    {
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->getStatus() === 'succeeded';
        }

        return parent::isCancelled();
    }

    /**
     * @inheritdoc
     */
    public function isRedirect()
    {
        if (isset($this->data['data']) && $this->getStatus() === 'awaiting_next_action') {
            // Currently this gateway supports only manual confirmation, so any other
            // next action types pretty much mean a failed transaction for us.
            return (!empty($this->data['data']['attributes']['next_action']['type']) && $this->data['data']['attributes']['next_action']['type'] === 'redirect');
        }

        return parent::isRedirect();
    }

    /**
     * @inheritdoc
     */
    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data['data']['attributes']['next_action']['redirect']['url'] : false;
    }

    /**
     * Get the payment intent reference.
     *
     * @return string|null
     */
    public function getPaymentIntentReference()
    {
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return $this->data['data']['id'];
        }

        return null;
    }

    /**
     * @return string|void|null
     */
    public function getTransactionId()
    {
        // we store the transaction id in the metadata
        if (isset($this->data['data']) && 'payment_intent' === $this->data['data']['type']) {
            return isset($this->data['data']['attributes']['metadata']['transactionId']) ? $this->data['data']['attributes']['metadata']['transactionId'] : null;
        }

        return null;
    }

    /**
     * Get the payment intent reference.
     *
     * @return string|null
     */
    public function getPaymentMethodReference()
    {
        if (isset($this->data['data']) && 'payment_method' === $this->data['data']['type']) {
            return $this->data['data']['id'];
        }

        return null;
    }
}
