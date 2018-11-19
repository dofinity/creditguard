<?php
require __DIR__ . '/vendor/autoload.php';

$generator = new \Wsdl2PhpGenerator\Generator();
$generator->generate(
  new \Wsdl2PhpGenerator\Config(array(
    'inputFile' => $_GET['wsdl_path'],
    'outputDir' => './wsdl'
  ))
);
