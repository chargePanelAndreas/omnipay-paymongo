<?php

/**
 * Paymongo Abstract Request.
 */

namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Paymongo\Message\Sources\Response;

/**
 * Paymongo Payment Intent Abstract Request.
 *
 * This is the parent class for all Paymongo payment intent requests.
 * It adds just a getter and setter.
 *
 * @see \Omnipay\Paymongo\SourcesGateway
 * @see \Omnipay\Paymongo\Message\AbstractRequest
 * @link https://paymongo.com/docs/api/payment_intents
 * @method Response send()
 */
abstract class AbstractRequest extends \Omnipay\Paymongo\Message\AbstractRequest
{
    /**
     * @param string $value
     *
     * @return \Omnipay\Paymongo\Message\Sources\AbstractRequest provides a fluent interface.
     */
    public function setSourceId($value)
    {
        return $this->setParameter('sourceId', $value);
    }

    /**
     * @return mixed
     */
    public function getSourceId()
    {
        return $this->getParameter('sourceId');
    }

    /**
     * @param $data
     * @param $statusCode
     * @return \Omnipay\Paymongo\Message\Sources\Response
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }
}
