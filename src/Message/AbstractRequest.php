<?php

/**
 * Paymongo Abstract Request.
 */

namespace Omnipay\Paymongo\Message;

use Money\Currency;
use Money\Money;
use Money\Number;
use Money\Parser\DecimalMoneyParser;
use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Paymongo Abstract Request.
 *
 * This is the parent class for all Paymongo requests.
 *
 * Test modes:
 *
 * Paymongo accounts have test-mode API keys as well as live-mode
 * API keys. These keys can be active at the same time. Data
 * created with test-mode credentials will never hit the credit
 * card networks and will never cost anyone money.
 *
 * Unlike some gateways, there is no test mode endpoint separate
 * to the live mode endpoint, the Paymongo API endpoint is the same
 * for test and for live.
 *
 * Setting the testMode flag on this gateway has no effect.  To
 * use test mode just use your test mode API key.
 *
 * You can use any of the cards listed at https://Paymongo.com/docs/testing
 * for testing.
 *
 * @see \Omnipay\Paymongo\Gateway
 * @link https://Paymongo.com/docs/api
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * Live or Test Endpoint URL.
     *
     * @var string URL
     */
    protected $endpoint = 'https://api.paymongo.com/v1';

    /**
     * Get the gateway API Key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * Set the gateway API Key.
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    abstract public function getEndpoint();

    /**
     * @param string $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setStatementDescriptor($value)
    {
        return $this->setParameter('statementDescriptor', $value);
    }

    /**
     * @return mixed
     */
    public function getStatementDescriptor()
    {
        return $this->getParameter('statementDescriptor');
    }

    /**
     * Get HTTP Method.
     *
     * This is nearly always POST but can be over-ridden in sub classes.
     *
     * @return string
     */
    public function getHttpMethod()
    {
        return 'POST';
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $headers = array(
            'Authorization' => 'Basic ' . base64_encode($this->getApiKey() . ':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        );

        if ($this->getHttpMethod() == 'GET') {
            $httpResponse = $this->httpClient->request('GET', $this->getEndpoint() . '?' . http_build_query($data), $headers);
        } else {
            $httpResponse = $this->httpClient->request('POST',  $this->getEndpoint(), $headers, json_encode($data));
        }

        return $this->createResponse(json_decode((string) $httpResponse->getBody(), true), $httpResponse->getStatusCode());
    }

    protected function createResponse($data, $statusCode)
    {
        return $this->response = new Response($this, $data, $statusCode);
    }

    public function getMetadata()
    {
        return $this->getParameter('metadata');
    }

    public function setMetadata($value)
    {
        return $this->setParameter('metadata', $value);
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParameter('source');
    }

    /**
     * @param $value
     *
     * @return AbstractRequest provides a fluent interface.
     */
    public function setSource($value)
    {
        return $this->setParameter('source', $value);
    }

    /**
     * @inheritdoc
     */
    public function getCardData()
    {
        $card = $this->getCard();
        $card->validate();

        $data = array();
        $data['type'] = 'card';

        $data['details']['card_number'] = $card->getNumber();
        $data['details']['exp_month'] = $card->getExpiryMonth();
        $data['details']['exp_year'] = $card->getExpiryYear();
        $data['details']['cvc'] = $card->getCvv();

        $data['billing']['address']['line1'] = $card->getAddress1();
        $data['billing']['address']['line2'] = $card->getAddress2();
        $data['billing']['address']['city'] = $card->getCity();
        $data['billing']['address']['state'] = $card->getState();
        $data['billing']['address']['postal_code'] = $card->getPostcode();
        $data['billing']['address']['country'] = $card->getCountry();

        $data['billing']['name'] = $card->getName();
        $data['billing']['phone'] = $card->getPhone();
        $data['billing']['email'] = $card->getEmail();

        return $data;
    }
}
