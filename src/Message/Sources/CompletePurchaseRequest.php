<?php

/**
 * Paymongo Payment Intents Authorize Request.
 */
namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Common\Exception\InvalidRequestException;

/**
 * Paymongo Payment Intents Purchase Request.
 *
 * A payment method is required. It can be set using the `paymentMethod`, `source`,
 * `cardReference` or `token` parameters.
 *
 * *Important*: Please note, that this gateway is a hybrid between credit card and
 * off-site gateway. It acts as a normal credit card gateway, unless the payment method
 * requires 3DS authentication, in which case it also performs a redirect to an
 * off-site authentication form.
 *
 * Example:
 *
 * <code>
 *   // Create a gateway for the Paymongo Gateway
 *   // (routes to GatewayFactory::create)
 *   $gateway = Omnipay::create('Paymongo\PaymentIntents');
 *
 *   // Initialise the gateway
 *   $gateway->initialize(array(
 *       'apiKey' => 'MyApiKey',
 *   ));
 *
 *   // Create a payment method using a credit card object.
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
 *   $paymentMethod = $gateway->createCard(['card' => $card])->send()->getCardReference();
 *
 *   // Code above can be skipped if you use Paymongo.js and have a payment method reference
 *   // in the $paymentMethod variable already.
 *
 *   // Tokens API has been deprecated so this implementation does not support it. Paymongo Tokens
 *   // API does not handle 3D Secure Authentication. You may experience higher decline rates
 *   // using Tokens API because of this if most of your customers' credit cards require 3D
 *   // Secure authentication. If you already integrated with the Token, Paymongo highly suggest upgrading
 *   // to the Payment Intent workflow.
 *
 *   // Also note the setting of a return url. This is needed for cards that require
 *   // the 3DS 2.0 authentication. If you do not set a return url, payment with such
 *   // cards will fail.
 *
 *   // Do a purchase transaction on the gateway
 *   $paymentIntent = $gateway->purchase(array(
 *       'amount'                   => '10.00',
 *       'currency'                 => 'USD',
 *       'description'              => 'This is a test purchase transaction.',
 *       'paymentMethod'            => $paymentMethod,
 *       'returnUrl'                => $completePaymentUrl,
 *       'confirm'                  => true,
 *   ));
 *
 *   $paymentIntent = $paymentIntent->send();
 *
 *   $response = $paymentIntent->send();
 *
 *   // If you set the confirm to true when performing the authorize transaction,
 *   // resume here.
 *
 *   // 3DS 2.0 time!
 *   if ($response->isRedirect()) {
 *       $response->redirect();
 *   } else if ($response->isSuccessful()) {
 *       echo "Authorize transaction was successful!\n";
 *       $sale_id = $response->getTransactionReference();
 *       echo "Transaction reference = " . $sale_id . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\Paymongo\PaymentIntentsGateway
 * @see \Omnipay\Paymongo\Message\PaymentIntents\CreatePaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\ConfirmPaymentIntentRequest
 * @link https://paymongo.com/docs/api/payment_intents
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends PurchaseRequest
{

    /**
     * @return string
     */
    public function getSessionName()
    {
        return $this->getParameter('sessionName');
    }

    /**
     * @param string $value Parameter value
     * @return $this
     */
    public function setSessionName($value)
    {
        return $this->setParameter('sessionName', $value);
    }

    public function getData()
    {
        $this->validate('apiKey');
        $this->validate('amount', 'description');

        $sessionName = $this->getSessionName();

        if (empty($sessionName)) {
            $sessionName = $this->httpRequest->query->get('session');
        }

        if (empty($sessionName)) {
            throw new InvalidRequestException("The sessionName parameter or session query is required or invalid.");
        }

        // the transaction id
        $sourceId = $this->getSourceIdFromSession($sessionName);

        if (empty($sourceId)) {
            throw new InvalidRequestException("Unable to load Source id from session.");
        }

        $this->setSourceId($sourceId);

        $data = [];
        $data['attributes']['amount'] = $this->getAmountInteger();
        $data['attributes']['description'] = $this->getDescription();
        $data['attributes']['source']['type'] = 'source';
        $data['attributes']['source']['id'] = $this->getSourceId();
        $data['attributes']['currency'] = $this->getCurrency();
        $data['attributes']['statement_descriptor'] = $this->getStatementDescriptor();

        return [
            'data' => $data
        ];
    }

    public function getSourceIdFromSession($sessionName)
    {
        $abs_path = $this->getSourceSessionNameBasePath().DIRECTORY_SEPARATOR.$sessionName;

        if (file_exists($abs_path)) {
            return file_get_contents($abs_path);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/payments';
    }

    /**
     * @param $data
     * @param $statusCode
     * @return CompletePurchaseResponse
     */
    protected function createResponse($data, $statusCode)
    {
        return $this->response = new CompletePurchaseResponse($this, $data, $statusCode);
    }
}
