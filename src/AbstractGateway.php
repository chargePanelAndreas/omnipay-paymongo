<?php

/**
 * Paymongo base gateway.
 */
namespace Omnipay\Paymongo;

use Omnipay\Common\AbstractGateway as AbstractOmnipayGateway;

/**
 * Paymongo Gateway.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the Paymongo Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('Paymongo');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'apiKey' => 'MyApiKey',
 *   ));
 *
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   $card = new CreditCard(array(
 *               'firstName'    => 'Example',
 *               'lastName'     => 'Customer',
 *               'number'       => '4242424242424242',
 *               'expiryMonth'  => '01',
 *               'expiryYear'   => '2020',
 *               'cvv'          => '123',
 *               'email'                 => 'customer@example.com',
 *               'billingAddress1'       => '1 Scrubby Creek Road',
 *               'billingCountry'        => 'AU',
 *               'billingCity'           => 'Scrubby Creek',
 *               'billingPostcode'       => '4999',
 *               'billingState'          => 'QLD',
 *   ));
 *
 *   // Do a purchase transaction on the gateway
 *   $transaction = $gateway->purchase(array(
 *       'amount'                   => '10.00',
 *       'currency'                 => 'USD',
 *       'card'                     => $card,
 *   ));
 *   $response = $transaction->send();
 *   if ($response->isSuccessful()) {
 *       echo "Purchase transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *
 *       $balance_transaction_id = $response->getBalanceTransactionReference();
 *       echo "Balance Transaction reference = " . $balance_transaction_id . "\n";
 *   }
 * </code>
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
 * You can use any of the cards listed at https://paymongo.com/docs/testing
 * for testing.
 *
 * Authentication:
 *
 * Authentication is by means of a single secret API key set as
 * the apiKey parameter when creating the gateway object.
 *
 * @see \Omnipay\Common\AbstractGateway
 * @see \Omnipay\Paymongo\Message\AbstractRequest
 *
 * @link https://paymongo.com/docs/api
 *
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 */
abstract class AbstractGateway extends AbstractOmnipayGateway
{

    /**
     * @inheritdoc
     */
    abstract public function getName();

    /**
     * Get the gateway parameters.
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'apiKey' => '',
            'paymongoVersion' => null
        );
    }

    /**
     * Get the gateway API Key.
     *
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
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
     * Authentication is by means of a single secret API key set as
     * the apiKey parameter when creating the gateway object.
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
     * @param string $value
     *
     * @return Gateway provides a fluent interface.
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * Purchase request.
     *
     * To charge a credit card, you create a new charge object. If your API key
     * is in test mode, the supplied card won't actually be charged, though
     * everything else will occur as if in live mode. (Paymongo assumes that the
     * charge would have completed successfully).
     *
     * Either a customerReference or a card is required.  If a customerReference
     * is passed in then the cardReference must be the reference of a card
     * assigned to the customer.  Otherwise, if you do not pass a customer ID,
     * the card you provide must either be a token, like the ones returned by
     * Paymongo.js, or a dictionary containing a user's credit card details.
     *
     * IN OTHER WORDS: You cannot just pass a card reference into this request,
     * you must also provide a customer reference if you want to use a stored
     * card.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Paymongo\Message\AbstractRequest
     */
    abstract public function purchase(array $parameters = array());

    /**
     * Fetch a transaction.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Paymongo\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\FetchTransactionRequest', $parameters);
    }

    /**
     * @param array $parameters
     * @return \Omnipay\Paymongo\Message\FetchBalanceTransactionRequest
     */
    public function fetchBalanceTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\FetchBalanceTransactionRequest', $parameters);
    }

    //
    // Payments
    // @link https://developers.paymongo.com/reference#payment-source
    //

    /**
     * Create Payment.
     *
     * A Payment resource is an attempt by your customer to send you money in exchange for your product.
     * This is a reference to an amount that you are expecting to receive if a payment resource with paid
     * status becomes a part of a payout. If the payment status is failed, you can determine the reason for failure.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Paymongo\Message\AbstractRequest
     */
    public function createPayment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\CreatePaymentRequest', $parameters);
    }

    /**
     * Fetch Payment.
     *
     * @param array $parameters
     *
     * @return \Omnipay\Paymongo\Message\AbstractRequest
     */
    public function fetchPayment(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\FetchPaymentRequest', $parameters);
    }

    /**
     * List Payments
     *
     * @param array $parameters
     *
     * @return \Omnipay\Paymongo\Message\AbstractRequest
     */
    public function listPayments(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paymongo\Message\ListPaymentsRequest', $parameters);
    }

}
