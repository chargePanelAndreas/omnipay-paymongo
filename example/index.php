<?php
$config = include __DIR__.DIRECTORY_SEPARATOR.'config.php';
require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';
?>
<!-- Bootstrap CSS only (https://getbootstrap.comhttps://getbootstrap.com/docs/5.1/examples/checkout/) -->

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Checkout example · Bootstrap v5.1</title>

    <link rel="canonical" href="https://getbootstrap.comhttps://getbootstrap.com/docs/5.1/examples/checkout/">



    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/5.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
    <link rel="icon" href="https://getbootstrap.com/docs/5.1/assets/img/favicons/favicon.ico">
    <meta name="theme-color" content="#7952b3">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
        .container {
            max-width: 960px;
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <main>
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <h2>Paymongo Example Checkout</h2>
            <p class="lead">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
        </div>

        <div class="row g-5">
            <div class="col-md-5 col-lg-4 order-md-last">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-primary">Your cart</span>
                    <span class="badge bg-primary rounded-pill">1</span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0">Product name</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">₱120.12</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (USD)</span>
                        <strong>₱120.12</strong>
                    </li>
                </ul>
            </div>
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Billing address</h4>
                <form method="post" action="process.php" class="needs-validation" >
                    <input type="hidden" value="120.12" class="js-amount">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label">First name</label>
                            <input type="text" class="form-control" name="first_name" id="firstName" placeholder="" value="John" required>
                            <div class="invalid-feedback">
                                Valid first name is required.
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="lastName" class="form-label">Last name</label>
                            <input type="text" class="form-control" name="last_name" id="lastName" placeholder="" value="Doe" required>
                            <div class="invalid-feedback">
                                Valid last name is required.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
                            <input type="email" value="johndoe@example.com" name="email" class="form-control" id="email" placeholder="you@example.com">
                            <div class="invalid-feedback">
                                Please enter a valid email address for shipping updates.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" id="address" placeholder="1234 Main St" value="Purok, Sambag" required>
                            <div class="invalid-feedback">
                                Please enter your shipping address.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" name="address2" id="address2" placeholder="Apartment or suite">
                        </div>


                        <div class="col-12">
                            <label for="address2" class="form-label">City </label>
                            <input type="text" value="Moalboal" name="city" class="form-control" id="city" placeholder="City" required>
                        </div>

                        <div class="col-md-5">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Choose...</option>
                                <option selected value="PH">Philippines</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid country.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <select class="form-select" id="state" name="state" required>
                                <option value="">Choose...</option>
                                <option selected value="cebu">Cebu</option>
                            </select>
                            <div class="invalid-feedback">
                                Please provide a valid state.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="zip" class="form-label">Zip</label>
                            <input type="text" value="6000" name="postal_code" class="form-control" id="zip" placeholder="" required>
                            <div class="invalid-feedback">
                                Zip code required.
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="address2" class="form-label">Phone </label>
                            <input type="text" value="+639100000000" name="phone" class="form-control" id="city" placeholder="City" required>
                        </div>

                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Payment</h4>

                    <div class="my-3">
                        <div class="form-check">
                            <input id="credit" name="payment_method" value="credit" type="radio" class="js-payment-method form-check-input" checked required>
                            <label class="form-check-label" for="credit">Credit card</label>
                        </div>
                        <div class="form-check">
                            <input id="gcash" name="payment_method" value="gcash" type="radio" class="js-payment-method form-check-input"  required>
                            <label class="form-check-label" for="gcash">Gcash</label>
                        </div>
                        <div class="form-check">
                            <input id="grabPay" name="payment_method" value="grabPay" type="radio" class="js-payment-method form-check-input"  required>
                            <label class="form-check-label" for="grabPay">Grab Pay</label>
                        </div>
                    </div>

                    <div class="row gy-3 js-payment-method-content js-payment-method-content-credit" style="display: none;">
                        <div class="col-md-6">
                            <label for="cc-name" class="form-label">Name on card</label>
                            <input value="John Doe" name="card_name" type="text" class="form-control" id="cc-name" placeholder="" required>
                        </div>

                        <div class="col-md-6">
                            <label for="cc-number" class="form-label">Credit card number</label>
                            <input value="4120000000000007" name="card_number" type="text" class="form-control" id="cc-number" placeholder="" required>
                        </div>

                        <div class="col-md-12">
                            <small class="text-muted">Use "4343434343434345" for normal card and Use "4120000000000007" for 3d secure.
                                <a href="https://developers.paymongo.com/docs/testing" target="_blank">More Testing Cards here</a></small>
                            <div class="invalid-feedback">
                                Name on card is required
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="cc-expiration" class="form-label">Expiration</label>
                            <input value="10/26" type="text" name="card_month_year" class="form-control" id="cc-expiration" placeholder="" required>
                            <div class="invalid-feedback">
                                Expiration date required
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="cc-cvv" class="form-label">CVV</label>
                            <input value="123" type="text" name="card_cvv" class="form-control" id="cc-cvv" placeholder="" required>
                            <div class="invalid-feedback">
                                Security code required
                            </div>
                        </div>
                    </div>
                    <div class="row gy-3 js-payment-method-content js-payment-method-content-gcash" style="display: none;">
                        <div class="col-md-12">
                            You will be redirected to Paymongo to process Gcash payment.
                        </div>
                    </div>
                    <div class="row gy-3 js-payment-method-content js-payment-method-content-grabPay" style="display: none;">
                        <div class="col-md-12">
                            You will be redirected to Paymongo to process Gcash payment.
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2017–2021 Company Name</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
    </footer>
</div>

<script src="https://getbootstrap.com/docs/5.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    crossorigin="anonymous"></script>

<script src="assets/paymongo.js"></script>

<script>
    Paymongo.setApiKey(<?php echo json_encode($config['publicKey']) ?>);

    jQuery(function($) {
        function getFormData($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        }

        // payment method content
        $(".js-payment-method").change(function() {
            var $this = $(this),
                $target = $('.js-payment-method-content-' + $this.val());

            $('.js-payment-method-content').hide();
            $target.show();
        }).filter(':checked').trigger('change');

        $('form').submit(function(e) {
            var $form = $(this);

            if ($form.find('.js-payment-method').val() == 'credit') {
                if (!$form.find('.js-paymongo-paymentMethodId-id').length) {
                    e.preventDefault();
                    var formData = getFormData($form);
                    $form.addClass('disabled');

                    Paymongo.createPaymentMethod({
                        "data": {
                            "attributes": {
                                "details": {
                                    "card_number": formData.card_number,
                                    // in real world, this should be validated
                                    // Note! should be an integer type so paymongo would not throw an error
                                    "exp_month": parseInt(formData.card_month_year.split('/')[0]),
                                    "exp_year": parseInt(formData.card_month_year.split('/')[1]),
                                    "cvc": formData.card_cvv
                                },
                                "billing": {
                                    "address": {
                                        "line1": formData.address1,
                                        "line2": formData.address2,
                                        "city": formData.city,
                                        "state": formData.state,
                                        "postal_code": formData.postal_code,
                                        "country": formData.country
                                    },
                                    "name": formData.card_name,
                                    "email": formData.email,
                                    "phone": formData.phone
                                },
                                "type": "card"
                            }
                        }
                    }, function (response, status) {
                        $form.removeClass('disabled');

                        if (status >= 400) {
                            alert('Error: Please check console logs.')
                        } else {
                            $form.append($('<input class="js-paymongo-paymentMethodId-id" type="hidden" name="paymentMethodId" value>').val(response.data.id));
                            // remove credit card form before submission to comply PCI compliance
                            $form.find('.js-payment-method-content-credit').remove();
                            // submit again
                            $form.submit();
                        }
                    });
                }
            } else {
                $form.addClass('disabled');
            }
        });
    });
</script>
</body>
</html>

