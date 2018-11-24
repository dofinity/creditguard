[![Maintainability](https://api.codeclimate.com/v1/badges/2f80d58cecaca809ed68/maintainability)](https://codeclimate.com/github/dofinity/creditguard/maintainability)

# CreditGuard
A lightweight PHP helper library for integrating CreditGuard API payments.

## Installation
### Install the library via Composer
```shell
$ composer require dofinity/creditguard:dev-master
```
### Using custom WSDL (customized API from CreditGuard)
At the moment in order to use custom classes, you have two options:
1. Export your changes as patch and apply that on the library (not recommended)
2. Fork the repository and apply your custom WSDL

##### Generating CG Classes via WSDL
As mentioned above, the library comes with a pre-generated CG classes, generated from our demo endpoint.
If your terminal provides a different classes that are customized for your specific needs, you will to generate the classes again
using `wsdl2phpgenerator` which is already defined as a dev dependency under our composer.json.

```shell
$ composer install --dev
   ```

Then execute `generateWsdl.php?wsdl_path=https%3A%2F%2Fxxx.creditguard.co.il%2Fxpo%2Fservices%2FRelay%3Fwsdl` and you should now see the updated classes in your `/wsdl` directory.

Note that the URL must be encoded for that to work.

### Security considerations and tweaks
- Make sure that wsdlGenerate.php is read only on production environment (400)
- Don't use `composer install --dev` on non development environments

## Basic usage

### Payment page setup
`examples/newTransactionRedirectUrl.php`

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
