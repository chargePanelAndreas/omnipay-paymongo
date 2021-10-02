<?php

/**
 * Paymongo Payment Intents Response.
 */
namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Paymongo\Message\Sources\Response as BaseResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * Paymongo Payment Intents Response.
 *
 * This is the response class for all payment intents related responses.
 *
 * @see \Omnipay\Paymongo\SourcesGateway
 */
class PurchaseResponse extends BaseResponse implements RedirectResponseInterface
{
    /**
     * @inheritdoc
     */
    public function isSuccessful()
    {
        return false;
    }

}
