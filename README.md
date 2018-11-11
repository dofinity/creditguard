[![Maintainability](https://api.codeclimate.com/v1/badges/2f80d58cecaca809ed68/maintainability)](https://codeclimate.com/github/dofinity/creditguard/maintainability)

# CreditGuard
A lightweight PHP helper library for integrating CreditGuard API payments.

## Installation with Composer
```shell
$ composer require dofinity/creditguard:dev-master
```

## Basic usage

### Payment page setup
```php
require __DIR__ . '/vendor/autoload.php';

// change terminal, user and password to real credentials
$terminal = '0123456';
$user = 'user';
$password = 'password';

// change to your own callback url
$GoodURL = 'http://yourdomain/callback.php';
$Total = 100;

// PaymentRequest accepts a lot of params, but in this case we use only required ones
$PaymentRequest = new \Creditguard\PaymentRequest(
    $terminal, $user, $password, $GoodURL, $Total
);

$payment = new \Creditguard\CreditguardPayment();
$payment->setPaymentRequest($PaymentRequest);
$result = $payment->SubmitPaymentRequest();
$resultJson = json_decode($result, true);

$URL = $resultJson['URL'];
$ConfirmationKey = $resultJson['ConfirmationKey'];
$Error = $resultJson['Error'];

// redirect to payment page
header("Location: {$URL}");
```

### Payment validation
```php
// callback.php
require __DIR__ . '/vendor/autoload.php';

$CreditguardTransactionId = $_GET['CreditguardTransactionId'];
$CreditguardStatusCode = $_GET['CreditguardStatusCode'];
$ConfirmationKey = $_GET['ConfirmationKey'];
$Total = 100;

$PaymentResponse = new \Creditguard\PaymentResponse(
    $CreditguardStatusCode, $CreditguardTransactionId, '', '', $ConfirmationKey, 100
);

$payment = new \Creditguard\CreditguardPayment();
$payment->setPaymentResponse($PaymentResponse);

if ($payment->ValidatePayment()) {
    echo 'Ok. Payment has been verified';
} else {
    echo 'Fail. Payment forged';
}
```

### Retrieve Transaction info
```php
// callback.php
require __DIR__ . '/vendor/autoload.php';

// change terminal, user and password to real credentials
$terminal = '0123456';
$user = 'user';
$password = 'password';

$CreditguardTransactionId = $_GET['CreditguardTransactionId'];

$transaction = new \Creditguard\CreditguardTransaction(
    $terminal, $user, $password, $CreditguardTransactionId
);

// use properties from src/Creditguard/CreditguardTransaction.php class
var_dump($transaction);
```
