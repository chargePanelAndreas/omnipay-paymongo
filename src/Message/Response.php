<?php

namespace Omnipay\Paymongo\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * Response constructor.
     *
     * @param \Omnipay\Common\Message\RequestInterface $request
     * @param mixed                                    $data
     */
    public function __construct(RequestInterface $request, $data, $statusCode)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    /**
     * Is the transaction successful?
     *
     * @return bool
     */
    public function isSuccessful()
    {
        if ($this->isRedirect()) {
            return false;
        }

        return !isset($this->data['errors']) && $this->isStatusCodeValid();
    }

    /**
     * If response status code is below 400 means it is successful
     *
     * @return bool
     */
    protected function isStatusCodeValid()
    {
        return $this->getStatusCode() < 400;
    }

    /**
     * Get the first error message from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getMessage()
    {
        if (!$this->isSuccessful() && isset($this->data['errors'][0]['detail'])) {
            return $this->data['errors'][0]['detail'];
        }

        return null;
    }

    /**
     * Get the first error code from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getCode()
    {
        if (!$this->isSuccessful() && isset($this->data['errors'][0]['code'])) {
            return $this->data['errors'][0]['code'];
        }

        return null;
    }

    /**
     * Get all the errors code from the response.
     *
     * Returns null if the request was successful.
     *
     * @return string|null
     */
    public function getAllErrors()
    {
        if (!$this->isSuccessful() && isset($this->data['errors'])) {
            return $this->data['errors'];
        }

        return null;
    }

    /**
     * Do we need to redirect?
     *
     * If the value of redirect url is set, then we will redirect this user
     *
     * @return bool
     */
    public function isRedirect()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getRedirectUrl()
    {
        return null;
    }

    /**
     * Get the status code from the response.
     *
     * 2xx means successfull
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @inheritdoc
     */
    public function getTransactionReference()
    {
        if (isset($this->data['data']['id'])) {
            return $this->data['data']['id'];
        }
    }

    /**
     * @inheritdoc
     */
    public function getCustomerReference()
    {
        return null;
    }
}
