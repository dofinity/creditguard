<?php

namespace Creditguard;

use Creditguard\Wsdl\ashraitTransaction;
use Creditguard\Wsdl\ashraitTransactionResponse;
use Creditguard\Wsdl\RelayService;

class CgCommandRequestChargeToken extends CgCommandRequest
{

  protected $total;

  protected $cardToken;
  protected $cardExp;
  protected $txId;


  protected $successUrl = "";
  protected $errorUrl = "";
  protected $cancelUrl = "";

  protected $uniqueid;

  protected $doDealResponse;

  /**
   * CgCommandRequestPaymentFormUrl constructor.
   * @param $relayUrl
   * @param $user
   * @param $password
   * @param $terminalNumber
   * @param $mid
   */
  public function __construct($relayUrl, $user, $password, $terminalNumber, $mid) {
    parent::__construct($relayUrl, $user, $password, $terminalNumber, $mid);
    $this->command = 'doDeal';
    $this->uniqueid = uniqid();

    return $this;
  }

  /**
   * Execute command with the data and fetch result
   * @return \Creditguard\CgCommandRequestPaymentFormUrl
   */
  public function execute() {
    $requestData = $this
      ->prepareRequestData()
      ->buildRequestData();

    $rs = new RelayService([], $this->relayUrl);
    $ashrait = new ashraitTransaction($this->user, $this->password, $requestData);
    $ashraitTransaction = $rs->ashraitTransaction($ashrait);
    $transactionResponse = new ashraitTransactionResponse($ashraitTransaction);

    $ashraitTransactionReturn = $transactionResponse->getAshraitTransactionReturn();
    // getAshraitTransactionReturn returns an object for some reason
    $raw_result = $ashraitTransactionReturn->ashraitTransactionReturn;
    $raw_result = mb_convert_encoding($raw_result, 'ISO-8859-8', 'UTF-8');
    $this->doDealResponse = simplexml_load_string($raw_result);

    return $this;
  }

  /**
   * @param mixed $total
   * @return CgCommandRequestChargeToken
   */
  public function setTotal($total)
  {
    $this->total = $total;
    return $this;
  }

  /**
   * @param mixed $cardToken
   * @return CgCommandRequestChargeToken
   */
  public function setCardToken($cardToken)
  {
    $this->cardToken = $cardToken;
    return $this;
  }

  /**
   * @param mixed $cardExp
   * @return CgCommandRequestChargeToken
   */
  public function setCardExp($cardExp)
  {
    $this->cardExp = $cardExp;
    return $this;
  }

  /**
   * @param mixed $txId
   * @return CgCommandRequestChargeToken
   */
  public function setTxId($txId)
  {
    $this->txId = $txId;
    return $this;
  }



  /**
   * Creating the data array
   * @return $this
   */
  protected function prepareRequestData() {
    $this->rawData = array(
      'request' => array(
        'version' => $this->version,
        'language' => $this->language,
        'dateTime' => $this->dateTime,
        'command' => $this->command,
        $this->command => array(
          'terminalNumber' => $this->terminalNumber,
          'cardNo' => $this->cardToken,
          'cardExpiration' => $this->cardExp,
          'creditType' => 'RegularCredit',
          'currency' => 'ILS',
          'transactionCode' => 'Phone',
          'transactionType' => 'Debit',
          'total' => $this->total,
          'validation' => 'TxnSetup',
          'mid' => $this->mid,
          'uniqueid' => $this->uniqueid,
          'mpiValidation' => 'Token'
        )
      )
    );

    return $this;
  }
}
