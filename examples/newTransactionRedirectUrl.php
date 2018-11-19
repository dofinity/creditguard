<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../wsdl/autoload.php';

$terminal = '12345';
$user = 'user';
$password = 'password';
$mid = 54321;
$total = 100;

$date = date("d/m/Y H:i:s");
$uniqueid = uniqid();

// For more details on request structure, Please refer CreditGuard API documentation
// @todo: generate ashraitTransactionDetailsFactory
$int_in = <<<EOT
<ashrait>
        <request>
                <version>1000</version>
                <language>HEB</language>
                <dateTime>$date</dateTime>
                <command>doDeal</command>
                <requestId/>
                <doDeal>
                        <terminalNumber>$terminal</terminalNumber>
                        <cardNo>CGMPI</cardNo>
                        <creditType>RegularCredit</creditType>
                        <currency>ILS</currency>
                        <transactionCode>Phone</transactionCode>
                        <transactionType>Debit</transactionType>
                        <total>$total</total>
                        <validation>TxnSetup</validation>
                        <mid>$mid</mid>
                        <uniqueid>$uniqueid</uniqueid>
                        <mpiValidation>autoComm</mpiValidation>
                        <numberOfPayments>1</numberOfPayments>
                        <email>email@example.com</email>
                        <description>Transcation notes here</description>
                        <successUrl>https://example.com/?success</successUrl>
                        <errorUrl>https://example.com/?error</errorUrl>
                        <cancelUrl>https://example.com/?cancel</cancelUrl>
                        </customerData>
                </doDeal>
        </request>
</ashrait>
EOT;

$ashrait = new ashraitTransaction($user, $password, $int_in);
$rs = new RelayService();
$result = new ashraitTransactionResponse($rs->ashraitTransaction($ashrait));

// @todo: generate ashraitTransactionResponseParser
print_r($result->getAshraitTransactionReturn());
