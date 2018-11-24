<?php

namespace Creditguard\Wsdl;

class ashraitTransactionResponse
{

    /**
     * @var string $ashraitTransactionReturn
     */
    protected $ashraitTransactionReturn = null;

    /**
     * @param string $ashraitTransactionReturn
     */
    public function __construct($ashraitTransactionReturn)
    {
      $this->ashraitTransactionReturn = $ashraitTransactionReturn;
    }

    /**
     * @return string
     */
    public function getAshraitTransactionReturn()
    {
      return $this->ashraitTransactionReturn;
    }

    /**
     * @param string $ashraitTransactionReturn
     * @return ashraitTransactionResponse
     */
    public function setAshraitTransactionReturn($ashraitTransactionReturn)
    {
      $this->ashraitTransactionReturn = $ashraitTransactionReturn;
      return $this;
    }

}
