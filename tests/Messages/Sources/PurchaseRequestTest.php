<?php

namespace Omnipay\Paymongo\Message\Sources;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @var PurchaseRequestTest
     */
    private $request;

    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '100.00',
                'currency' => 'PHP',
                'type' => PurchaseRequest::TYPE_GCASH,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'address1' => 'Level 21, 459 Collins st',
                'address2' => 'address 2 info',
                'city' => 'Melbourne',
                'postCode' => '3000',
                'state' => 'VIC',
                'country' => 'AU',
                'phone' => '+61 7 7010 1111',
                'email' => 'johndoe@example.com',
                'returnUrl' => 'http://paymongo.local/return',
            )
        );
        $this->request->setApiKey('some_api');
    }

    public function testType()
    {

        $this->assertSame($this->request, $this->request->setType('something'));
        $this->assertSame('something', $this->request->getType());
    }

    public function testFirstName()
    {
        $this->assertSame($this->request, $this->request->setFirstName('something'));
        $this->assertSame('something', $this->request->getFirstName());
    }

    public function testLastName()
    {
        $this->assertSame($this->request, $this->request->setLastName('something'));
        $this->assertSame('something', $this->request->getLastName());
    }

    public function testName()
    {
        $this->assertSame($this->request, $this->request->setName('something'));
        $this->assertSame('something', $this->request->getName());
    }

    public function testBillingName()
    {
        $this->assertSame($this->request, $this->request->setBillingName('something'));
        $this->assertSame('something', $this->request->getBillingName());
    }

    public function testBillingFirstName()
    {
        $this->assertSame($this->request, $this->request->setBillingFirstName('something'));
        $this->assertSame('something', $this->request->getBillingFirstName());
    }

    public function testBillingLastName()
    {
        $this->assertSame($this->request, $this->request->setBillingLastName('something'));
        $this->assertSame('something', $this->request->getBillingLastName());
    }

    public function testBillingAddress1()
    {
        $this->assertSame($this->request, $this->request->setBillingAddress1('something'));
        $this->assertSame('something', $this->request->getBillingAddress1());
    }

    public function testBillingAddress2()
    {
        $this->assertSame($this->request, $this->request->setBillingAddress2('something'));
        $this->assertSame('something', $this->request->getBillingAddress2());
    }

    public function testBillingCity()
    {
        $this->assertSame($this->request, $this->request->setBillingCity('something'));
        $this->assertSame('something', $this->request->getBillingCity());
    }

    public function testBillingPostcode()
    {
        $this->assertSame($this->request, $this->request->setBillingPostcode('something'));
        $this->assertSame('something', $this->request->getBillingPostcode());
    }

    public function testBillingState()
    {
        $this->assertSame($this->request, $this->request->setBillingState('something'));
        $this->assertSame('something', $this->request->getBillingState());
    }

    public function testBillingCountry()
    {
        $this->assertSame($this->request, $this->request->setBillingCountry('something'));
        $this->assertSame('something', $this->request->getBillingCountry());
    }

    public function testBillingPhone()
    {
        $this->assertSame($this->request, $this->request->setBillingPhone('something'));
        $this->assertSame('something', $this->request->getBillingPhone());
    }

    public function testAddress1()
    {
        $this->assertSame($this->request, $this->request->setAddress1('something'));
        $this->assertSame('something', $this->request->getAddress1());
    }

    public function testAddress2()
    {
        $this->assertSame($this->request, $this->request->setAddress2('something'));
        $this->assertSame('something', $this->request->getAddress2());
    }

    public function testCity()
    {
        $this->assertSame($this->request, $this->request->setCity('something'));
        $this->assertSame('something', $this->request->getCity());
    }

    public function testPostcode()
    {
        $this->assertSame($this->request, $this->request->setPostcode('something'));
        $this->assertSame('something', $this->request->getPostcode());
    }

    public function testState()
    {
        $this->assertSame($this->request, $this->request->setState('something'));
        $this->assertSame('something', $this->request->getState());
    }

    public function testCountry()
    {
        $this->assertSame($this->request, $this->request->setCountry('something'));
        $this->assertSame('something', $this->request->getCountry());
    }

    public function testPhone()
    {
        $this->assertSame($this->request, $this->request->setPhone('something'));
        $this->assertSame('something', $this->request->getPhone());
    }

    public function testEmail()
    {
        $this->assertSame($this->request, $this->request->setEmail('something'));
        $this->assertSame('something', $this->request->getEmail());
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame(10000, $data['data']['attributes']['amount']);
        $this->assertSame('PHP', $data['data']['attributes']['currency']);
        $this->assertSame('gcash', $data['data']['attributes']['type']);

        $sessionName = $this->request->getSourceIdSessionName();
        $successQuery = [
            'session' => $sessionName,
            'status' => 'success',
        ];
        $failedQuery = [
            'session' => $sessionName,
            'status' => 'failed',
        ];
        $this->assertSame('http://paymongo.local/return?'.http_build_query($successQuery), $data['data']['attributes']['redirect']['success']);
        $this->assertSame('http://paymongo.local/return?'.http_build_query($failedQuery), $data['data']['attributes']['redirect']['failed']);

        $this->assertSame('Level 21, 459 Collins st', $data['data']['attributes']['billing']['address']['line1']);
        $this->assertSame('address 2 info', $data['data']['attributes']['billing']['address']['line2']);
        $this->assertSame('Melbourne', $data['data']['attributes']['billing']['address']['city']);
        $this->assertSame('VIC', $data['data']['attributes']['billing']['address']['state']);
        $this->assertSame('3000', $data['data']['attributes']['billing']['address']['postal_code']);
        $this->assertSame('AU', $data['data']['attributes']['billing']['address']['country']);

        $this->assertSame('+61 7 7010 1111', $data['data']['attributes']['billing']['phone']);
        $this->assertSame('John Doe', $data['data']['attributes']['billing']['name']);
        $this->assertSame('johndoe@example.com', $data['data']['attributes']['billing']['email']);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('PurchaseSourceSuccess.txt');
        /** @var Response $response */
        $response = $this->request->send();

        $this->assertNotEmpty($response->getStatus());
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('src_XpHZz6C2wk112P9Mufguhcch', $response->getTransactionReference());
        $this->assertSame('https://test-sources.paymongo.com/sources?id=src_XpHZz6C2wk112P9Mufguhcch', $response->getRedirectUrl());
        $this->assertNull($response->getMessage());

        $this->assertSame('src_XpHZz6C2wk112P9Mufguhcch', file_get_contents($this->request->getSourceIdSessionAbsPath()));
    }

    public function testSendError()
    {
        $this->setMockHttpResponse('PurchaseSourceError.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNotNull($response->getMessage());
    }
}

