<?php

namespace Omnipay\Paymongo\Message\Checkout;

use Omnipay\Common\Message\NotificationInterface;

class AcceptNotification extends AbstractRequest implements NotificationInterface
{
    private string $transactionReference;
    private string $eventType;
    private string $message;
    private array $data;
    private bool $success = false;
    private array $request;

    function sendData($parameters) : self
    {
        try {
            $this->eventType = $parameters['data']['attributes']['type'] ?? '';
            if ($this->eventType !== 'checkout_session.payment.paid') {
                $this->success = false;
            } else {
                $checkoutSession = $parameters['data']['attributes']['data']['attributes'];
                $this->transactionReference = $parameters['data']['attributes']['data']['id'];
                $paymentMethod = $checkoutSession['payments'][0]['attributes']['source'] ?? [];
                unset($paymentMethod['id']);
                $this->message = 'Paid with ' . ($paymentMethod ? json_encode($paymentMethod) : $checkoutSession['payment_method_used']);
                $this->data = $checkoutSession;
                $this->success = $this->getTransactionStatus() === NotificationInterface::STATUS_COMPLETED;
            }
        } catch (\Exception $e) {
            $this->success = false;
        }

        return $this;
    }

    function getTransactionReference()
    {
        return $this->transactionReference;
    }

    function getTransactionStatus()
    {
        if($this->eventType === 'checkout_session.payment.paid')
        {
            return NotificationInterface::STATUS_COMPLETED;
        }

        return NotificationInterface::STATUS_PENDING;
    }

    function getMessage()
    {
        return $this->message;
    }

    function getData()
    {
        return $this->data;
    }

    function isSuccessful()
    {
        return $this->success;
    }

    function getRequest()
    {
        return $this->request;
    }

    function isRedirect()
    {
        return false;
    }

    function isCancelled()
    {
        return false;
    }

    function getCode()
    {
        if($this->success)
        {
            return 200;
        }
        else
        {
            return 400;
        }
    }

    public function getEndpoint()
    {
        return;
    }

    public function send($postData = null)
    {
        return $this->sendData($postData ?? json_decode($_POST, true, 512, JSON_THROW_ON_ERROR));
    }
}
