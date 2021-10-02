<?php

$config = include __DIR__.DIRECTORY_SEPARATOR.'config.php';
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Omnipay\Omnipay;
use Omnipay\Paymongo\Message\Sources\PurchaseRequest;

// Setup payment gateway

$paymentMethod = isset($_REQUEST['payment_method']) ? $_REQUEST['payment_method'] : 'credit';

// Example form data
$formData = [
    'amount' => '120.12',
    'currency' => 'PHP',
    'statementDescriptor' => 'Paymongo PH',
    'description' => 'Order #90',
    'paymentMethodIdAllowed' => 'card',
    'requestThreeDSecure' => 'automatic',
    'paymentMethod' => $_POST['paymentMethodId'],
    'returnUrl' => 'http://examplepaymongo.local.com/return.php?payment_method='.$paymentMethod,
    'transactionId' => '90',
];
if ($paymentMethod == 'credit') {
    $gateway = Omnipay::create('\Omnipay\Paymongo\PaymentIntentsGateway');
} else {
    $gateway = Omnipay::create('\Omnipay\Paymongo\SourceGateway');

    $formData = array_merge($formData, [
        'firstName' => $_POST['first_name'],
        'lastName' => $_POST['last_name'],
        'address1' => $_POST['address'],
        'address2' => $_POST['address2'],
        'city' => $_POST['city'],
        'postCode' => $_POST['postal_code'],
        'state' => $_POST['state'],
        'country' => $_POST['country'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'type' => ($paymentMethod == 'gcash' ?
            \Omnipay\Paymongo\Message\Sources\PurchaseRequest::TYPE_GCASH
            : \Omnipay\Paymongo\Message\Sources\PurchaseRequest::TYPE_GRAB_PAY
        )
    ]);
}

$_SESSION['last_form_data'] = $formData;

$gateway->setApiKey($config['secretKey']);


// Send purchase request
try {
    $response = $gateway->purchase($formData)->send();

    // Process response
    if ($response->isSuccessful()) {

        echo "<a href='index.php'>back</a>";
        echo "<h1>Succeeded</h1>";
        echo "<pre>";
        echo var_export($response->getData(), true);
        echo "</pre>";

    } elseif ($response->isRedirect()) {

        // Redirect to offsite payment gateway
        $response->redirect();

    } else {
        echo "<a href='index.php'>back</a>";
        // Payment failed
        echo "<h1>Failed</h1>";
        echo $response->getMessage();
        echo "<pre>";
        echo var_export($response->getData(), true);
        echo "</pre>";
    }
} catch (\Omnipay\Common\Exception\InvalidRequestException $e) {
    echo "<a href='index.php'>back</a>";
    ?>
        <h3><?php echo htmlentities($e->getMessage()); ?></h3>
    <?php
}
