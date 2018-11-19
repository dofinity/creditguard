<?php

class RelayService extends \SoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
      'ashraitTransaction' => '\\ashraitTransaction',
      'ashraitTransactionResponse' => '\\ashraitTransactionResponse',
    );

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     */
    public function __construct(array $options = array(), $wsdl = null)
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
      $options = array_merge(array (
      'features' => 1,
    ), $options);
      if (!$wsdl) {
        $wsdl = 'https://xxx.creditguard.co.il/xpo/services/Relay?wsdl';
      }
      parent::__construct($wsdl, $options);
    }

    /**
     * @param ashraitTransaction $parameters
     * @return ashraitTransactionResponse
     */
    public function ashraitTransaction(ashraitTransaction $parameters)
    {
      return $this->__soapCall('ashraitTransaction', array($parameters));
    }

}
