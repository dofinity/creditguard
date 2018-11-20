<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../wsdl/autoload.php';
require __DIR__ . '/../src/autoload.php';


$terminal = '12345';
$user = 'user';
$password = 'pass';
$mid = '54321';

$deal = new AshraitTransactionFactoryDoDeal($user, $password, $terminal, $mid);
$deal
  ->setTotal(100)
  ->setEmail("test@example.com")
  ->setDescription("good description here");

echo $deal->execute()->getRedirectUrl();
?>