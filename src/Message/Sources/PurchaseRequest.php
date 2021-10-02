<?php

/**
 * Paymongo Create Payment Method Request.
 */
namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Helper;
use Omnipay\Paymongo\Message\Response;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Paymongo Create Payment Method Request.
 *
 * Paymongo payment methods differs a little bit from creating a card.
 * When using the Payment Intent API, it is mandatory to use a payment method,
 * so a lot of times you'll be creating a payment method without an assigned customer.
 *
 * Another difference is that it's impossible to create a payment method and assign
 * it to a user in a single request. Instead, you create a payment method and then
 * attach it.
 *
 * ### Example
 *
 * <code>
 *   // Create a credit card object
 *   // This card can be used for testing.
 *   $new_card = new CreditCard([
 *       'firstName'     => 'Example',
 *       'lastName'      => 'Customer',
 *       'number'        => '5555555555554444',
 *       'expiryMonth'   => '01',
 *       'expiryYear'    => '2020',
 *       'cvv'           => '456',
 *       'email'             => 'customer@example.com',
 *       'billingAddress1'   => '1 Lower Creek Road',
 *       'billingCountry'    => 'AU',
 *       'billingCity'       => 'Upper Swan',
 *       'billingPostcode'   => '6999',
 *       'billingState'      => 'WA',
 *   ]);
 *
 *   // Do a create card transaction on the gateway
 *   $response = $gateway->createCard(['card' => $new_card])->send();
 *   if ($response->isSuccessful()) {
 *       echo "Gateway createCard was successful.\n";
 *       // Find the card ID
 *       $method_id = $response->getCardReference();
 *       echo "Method ID = " . $method_id . "\n";
 *   }
 * </code>
 *
 * @see \Omnipay\Paymongo\Message\PaymentIntents\AttachPaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\DetachPaymentMethodRequest
 * @see \Omnipay\Paymongo\Message\PaymentIntents\UpdatePaymentMethodRequest
 * @link https://paymongo.com/docs/api/payment_methods/create
 * @method PurchaseResponse send()
 */
class PurchaseRequest extends AbstractRequest
{
    const TYPE_GCASH = 'gcash';
    const TYPE_GRAB_PAY = 'grab_pay';

    protected $supported_types = [
        self::TYPE_GCASH,
        self::TYPE_GRAB_PAY,
    ];

    /**
     * @return string
     */
    public function getType()
    {
        return $this->getParameter('type');
    }

    /**
     * @param string $value Parameter value
     * @return $this
     */
    public function setType($value)
    {
        return $this->setParameter('type', $value);
    }

    /**
     * Get Card First Name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->getBillingFirstName();
    }

    /**
     * Set Card First Name (Billing).
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setFirstName($value)
    {
        $this->setBillingFirstName($value);

        return $this;
    }

    /**
     * Get Card Last Name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->getBillingLastName();
    }

    /**
     * Set Card Last Name (Billing).
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setLastName($value)
    {
        $this->setBillingLastName($value);

        return $this;
    }

    /**
     * Get Card Name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBillingName();
    }

    /**
     * Set Card Name (Billing).
     *
     * @param string $value Parameter value
     * @return $this
     */
    public function setName($value)
    {
        $this->setBillingName($value);

        return $this;
    }

    /**
     * Get the card billing name.
     *
     * @return string
     */
    public function getBillingName()
    {
        return trim($this->getBillingFirstName() . ' ' . $this->getBillingLastName());
    }

    /**
     * Split the full name in the first and last name.
     *
     * @param $fullName
     * @return array with first and lastname
     */
    protected function listFirstLastName($fullName)
    {
        $names = explode(' ', $fullName, 2);

        return [$names[0], isset($names[1]) ? $names[1] : null];
    }

    /**
     * Sets the card billing name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingName($value)
    {
        $names = $this->listFirstLastName($value);

        $this->setBillingFirstName($names[0]);
        $this->setBillingLastName($names[1]);

        return $this;
    }

    /**
     * Get the first part of the card billing name.
     *
     * @return string
     */
    public function getBillingFirstName()
    {
        return $this->getParameter('billingFirstName');
    }

    /**
     * Sets the first part of the card billing name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingFirstName($value)
    {
        return $this->setParameter('billingFirstName', $value);
    }

    /**
     * Get the last part of the card billing name.
     *
     * @return string
     */
    public function getBillingLastName()
    {
        return $this->getParameter('billingLastName');
    }

    /**
     * Sets the last part of the card billing name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingLastName($value)
    {
        return $this->setParameter('billingLastName', $value);
    }

    /**
     * Get the billing address, line 1.
     *
     * @return string
     */
    public function getBillingAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    /**
     * Sets the billing address, line 1.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingAddress1($value)
    {
        return $this->setParameter('billingAddress1', $value);
    }

    /**
     * Get the billing address, line 2.
     *
     * @return string
     */
    public function getBillingAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    /**
     * Sets the billing address, line 2.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingAddress2($value)
    {
        return $this->setParameter('billingAddress2', $value);
    }

    /**
     * Get the billing city.
     *
     * @return string
     */
    public function getBillingCity()
    {
        return $this->getParameter('billingCity');
    }

    /**
     * Sets billing city.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCity($value)
    {
        return $this->setParameter('billingCity', $value);
    }

    /**
     * Get the billing postcode.
     *
     * @return string
     */
    public function getBillingPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    /**
     * Sets the billing postcode.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPostcode($value)
    {
        return $this->setParameter('billingPostcode', $value);
    }

    /**
     * Get the billing state.
     *
     * @return string
     */
    public function getBillingState()
    {
        return $this->getParameter('billingState');
    }

    /**
     * Sets the billing state.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingState($value)
    {
        return $this->setParameter('billingState', $value);
    }

    /**
     * Get the billing country name.
     *
     * @return string
     */
    public function getBillingCountry()
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * Sets the billing country name.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingCountry($value)
    {
        return $this->setParameter('billingCountry', $value);
    }

    /**
     * Get the billing phone number.
     *
     * @return string
     */
    public function getBillingPhone()
    {
        return $this->getParameter('billingPhone');
    }

    /**
     * Sets the billing phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setBillingPhone($value)
    {
        return $this->setParameter('billingPhone', $value);
    }

    /**
     * Get the billing address, line 1.
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->getParameter('billingAddress1');
    }

    /**
     * Sets the billing address, line 1.
     *
     * @param string $value
     * @return $this
     */
    public function setAddress1($value)
    {
        $this->setParameter('billingAddress1', $value);

        return $this;
    }

    /**
     * Get the billing address, line 2.
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->getParameter('billingAddress2');
    }

    /**
     * Sets the billing address, line 2.
     *
     * @param string $value
     * @return $this
     */
    public function setAddress2($value)
    {
        $this->setParameter('billingAddress2', $value);

        return $this;
    }

    /**
     * Get the billing city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->getParameter('billingCity');
    }

    /**
     * Sets the billing city.
     *
     * @param string $value
     * @return $this
     */
    public function setCity($value)
    {
        $this->setParameter('billingCity', $value);

        return $this;
    }

    /**
     * Get the billing postcode.
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->getParameter('billingPostcode');
    }

    /**
     * Sets the billing postcode.
     *
     * @param string $value
     * @return $this
     */
    public function setPostcode($value)
    {
        $this->setParameter('billingPostcode', $value);

        return $this;
    }

    /**
     * Get the billing state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->getParameter('billingState');
    }

    /**
     * Sets the billing state.
     *
     * @param string $value
     * @return $this
     */
    public function setState($value)
    {
        $this->setParameter('billingState', $value);

        return $this;
    }

    /**
     * Get the billing country.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * Sets the billing country.
     *
     * @param string $value
     * @return $this
     */
    public function setCountry($value)
    {
        $this->setParameter('billingCountry', $value);

        return $this;
    }

    /**
     * Get the billing phone number.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->getParameter('billingPhone');
    }

    /**
     * Sets the billing phone number.
     *
     * @param string $value
     * @return $this
     */
    public function setPhone($value)
    {
        $this->setParameter('billingPhone', $value);

        return $this;
    }

    /**
     * Get the cardholder's email address.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Sets the cardholder's email address.
     *
     * @param string $value
     * @return $this
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = [];
        $this->validate('currency', 'amount', 'type', 'returnUrl');

        if (!in_array($this->getType(), [self::TYPE_GCASH, self::TYPE_GRAB_PAY])) {
            throw new InvalidRequestException("The type parameter is invalid");
        }

        $returnUrl = $this->getReturnUrl();
        // Append the session unique name to the success and failed url.
        // This session name will be stored on ./sessions path and will hold
        // the source id of the created source.
        $returnUrl .= strpos($returnUrl, '?') === false ? '?' : '&';
        $returnUrl .= http_build_query([
            'session' => $this->getSourceIdSessionName(),
            'status' => 'success',
        ]);

        $failedUrl = $this->getReturnUrl();
        // Failed url is almost the same with return url except that
        // it includes the status failed.
        $failedUrl .= strpos($failedUrl, '?') === false ? '?' : '&';
        $failedUrl .= http_build_query([
            'session' => $this->getSourceIdSessionName(),
            'status' => 'failed',
        ]);

        $data['attributes']['amount'] = $this->getAmountInteger();
        $data['attributes']['redirect']['success'] = $returnUrl;
        $data['attributes']['redirect']['failed'] = $failedUrl;
        $data['attributes']['billing']['address']['line1'] = $this->getAddress1();
        $data['attributes']['billing']['address']['line2'] = $this->getAddress2();
        $data['attributes']['billing']['address']['state'] = $this->getState();
        $data['attributes']['billing']['address']['postal_code'] = $this->getPostcode();
        $data['attributes']['billing']['address']['city'] = $this->getCity();
        $data['attributes']['billing']['address']['country'] = $this->getCountry();
        $data['attributes']['billing']['name'] = $this->getName();
        $data['attributes']['billing']['email'] = $this->getEmail();
        $data['attributes']['billing']['phone'] = $this->getPhone();
        $data['attributes']['type'] = $this->getType();
        $data['attributes']['currency'] = $this->getCurrency();

        return [
            'data' => $data
        ];
    }

    /**
     * @return false|string
     */
    public function getSourceIdSessionName()
    {
        static $sessionName = false;

        if ($sessionName === false) {
            $sessionName = md5(uniqid()).uniqid().time();
        }

        $this->sessionCleaner();

        return $sessionName;
    }

    /**
     * @return $this
     */
    public function saveSourceIdToSession($sourceId)
    {
        file_put_contents($this->getSourceIdSessionAbsPath(), $sourceId);
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceIdSessionAbsPath()
    {
        return $this->getSourceSessionNameBasePath(). DIRECTORY_SEPARATOR.$this->getSourceIdSessionName();
    }

    /**
     * @return string
     */
    protected function getSourceSessionNameBasePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR. 'sessions';
    }

    /**
     * Todo make this testable!
     */
    public function sessionCleaner()
    {
        // clean pdf
        $files = glob($this->getSourceSessionNameBasePath(). DIRECTORY_SEPARATOR."*");
        $now   = time();

        @mkdir($this->getSourceSessionNameBasePath(), 0755, true);

        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= (60 * 60 * 24 * 10)) { // 15 days
                    unlink($file);
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint()
    {
        return $this->endpoint.'/sources';
    }

    /**
     * @inheritdoc
     */
    protected function createResponse($data, $statusCode)
    {
        $response = $this->response = new PurchaseResponse($this, $data, $statusCode);

        if ($response->isRedirect()) {
            $this->saveSourceIdToSession($response->getTransactionReference());
        }

        return $response;
    }
}
