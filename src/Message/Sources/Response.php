<?php

/**
 * Paymongo Payment Intents Response.
 */
namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Paymongo\Message\Response as BaseResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymongo Payment Intents Response.
 *
 * This is the response class for all payment intents related responses.
 *
 * @see \Omnipay\Paymongo\SourcesGateway
 */
class Response extends BaseResponse implements RedirectResponseInterface
{

    /**
     * Get the status of a payment intents response.
     *
     * @return string|null
     */
    public function getStatus()
    {
        if (isset($this->data['data']) && 'source' === $this->data['data']['type']) {
            return $this->data['data']['attributes']['status'];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function isCancelled()
    {
        return $this->getStatus() === 'cancelled';
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return !isset($this->data['errors']) && $this->isStatusCodeValid();
    }

    /**
     * @inheritdoc
     */
    public function isPending()
    {
        return $this->getStatus() === 'pending';
    }

    /**
     * @inheritdoc
     */
    public function isExpired()
    {
        return $this->getStatus() === 'expired';
    }


    /**
     * @inheritdoc
     */
    public function isChargeable()
    {
        return $this->getStatus() === 'chargeable';
    }

    /**
     * @inheritdoc
     */
    public function isPaid()
    {
        return $this->getStatus() === 'paid';
    }

    /**
     * @inheritdoc
     */
    public function isRedirect()
    {
        if (isset($this->data['data']) && $this->isPending()) {
            // Currently this gateway supports only manual confirmation, so any other
            // next action types pretty much mean a failed transaction for us.
            return (!empty($this->data['data']['attributes']['redirect']['checkout_url']));
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getRedirectUrl()
    {
        return $this->isRedirect() ? $this->data['data']['attributes']['redirect']['checkout_url'] : false;
    }
}
