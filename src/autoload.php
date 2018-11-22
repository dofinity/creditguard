<?php

function autoload_6a101e3147b9c1451852dd8e76201236($class)
{
    $classes = array(
      'CgCommandRequest' => __DIR__ . '/Request/CgCommandRequest.php',
      'CgCommandRequestPaymentFormUrl' => __DIR__ . '/Request/CgCommandRequestPaymentFormUrl.php'
    );
    if (!empty($classes[$class])) {
        include $classes[$class];
    };
}

spl_autoload_register('autoload_6a101e3147b9c1451852dd8e76201236');

// Do nothing. The rest is just leftovers from the code generation.
{
}
