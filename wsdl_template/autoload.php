<?php


 function autoload_6a101e3147b9c1451852dd8e76207353($class)
{
    $classes = array(
        'RelayService' => __DIR__ .'/RelayService.php',
        'ashraitTransaction' => __DIR__ .'/ashraitTransaction.php',
        'ashraitTransactionResponse' => __DIR__ .'/ashraitTransactionResponse.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_6a101e3147b9c1451852dd8e76207353');

// Do nothing. The rest is just leftovers from the code generation.
{
}
