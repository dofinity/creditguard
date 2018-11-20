<?php

function autoload_6a101e3147b9c1451852dd8e76201236($class)
{
    $classes = array(
      'AshraitTransactionFactory' => __DIR__ . '/Factory/AshraitTransactionFactory.php',
      'AshraitTransactionFactoryDoDeal' => __DIR__ . '/Factory/AshraitTransactionFactoryDoDeal.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_6a101e3147b9c1451852dd8e76201236');

// Do nothing. The rest is just leftovers from the code generation.
{
}
