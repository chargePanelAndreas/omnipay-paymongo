<?php

$config = include __DIR__.DIRECTORY_SEPARATOR.'config.php';
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use Omnipay\Omnipay;

// Setup payment gateway
$input = $_SESSION['last_form_data'];

// Send purchase request
$paymentMethod = isset($_REQUEST['payment_method']) ? $_REQUEST['payment_method'] : 'credit';
// Example form data
$formData = [
    'amount' => '120.12',
    'currency' => 'PHP',
    'statementDescriptor' => 'Paymongo PH',
    'description' => 'Order #90',
    'paymentMethodIdAllowed' => 'card',
    'requestThreeDSecure' => 'automatic',
    'returnUrl' => 'http://examplepaymongo.local.com/return.php?payment_method='.$paymentMethod,
    'transactionId' => '90',
];
if ($paymentMethod == 'credit') {
    $gateway = Omnipay::create('\Omnipay\Paymongo\PaymentIntentsGateway');
} else {
    $gateway = Omnipay::create('\Omnipay\Paymongo\SourceGateway');

    $formData = array_merge($formData, [
        'firstName' => $input['first_name'],
        'lastName' => $input['last_name'],
        'address1' => $input['address'],
        'address2' => $input['address2'],
        'city' => $input['city'],
        'postCode' => $input['postal_code'],
        'state' => $input['state'],
        'country' => $input['country'],
        'phone' => $input['phone'],
        'email' => $input['email'],
        'type' => ($paymentMethod == 'gcash' ?
            \Omnipay\Paymongo\Message\Sources\PurchaseRequest::TYPE_GCASH
            : \Omnipay\Paymongo\Message\Sources\PurchaseRequest::TYPE_GRAB_PAY
        )
    ]);
}

$gateway->setApiKey($config['secretKey']);

try {
    $response = $gateway->completePurchase($formData)->send();

    // Process response
    if ($response->isSuccessful()) {

        // Payment was successful
        echo "<a href='index.php'>back</a>";
        echo "<h1>Succeeded</h1>";
        echo "<pre>";
        echo var_export($response->getData(), true);
        echo "</pre>";

    } elseif ($response->isRedirect()) {

        // Redirect to offsite payment gateway
        $response->redirect();

    } else {
        // Payment failed
        echo "<a href='index.php'>back</a>";
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
