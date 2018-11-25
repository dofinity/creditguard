<?php

namespace Creditguard;

use Creditguard\Wsdl\ashraitTransaction;
use Creditguard\Wsdl\ashraitTransactionResponse;
use Creditguard\Wsdl\RelayService;

class CgCommandRequestPaymentFormUrl extends CgCommandRequest
{

  protected $email;
  protected $description;
  protected $total;

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
   * @param mixed $email
   */
  public function setEmail($email)
  {
    $this->email = $email;
    return $this;
  }

  /**
   * @param mixed $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
    return $this;
  }

  /**
   * @param mixed $total
   */
  public function setTotal($total)
  {
    $this->total = $total;
    return $this;
  }

  /**
   * @param mixed $successUrl
   */
  public function setSuccessUrl($successUrl)
  {
    $this->successUrl = $successUrl;
    return $this;
  }

  /**
   * @param mixed $errorUrl
   */
  public function setErrorUrl($errorUrl)
  {
    $this->errorUrl = $errorUrl;
    return $this;
  }

  /**
   * @param mixed $cancelUrl
   */
  public function setCancelUrl($cancelUrl)
  {
    $this->cancelUrl = $cancelUrl;
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
   * Extract form url from response
   * @return url
   */
  public function getPaymentFormUrl() {
    return $this->doDealResponse->response->doDeal->mpiHostedPageUrl->__toString();
  }

  /**
   * Extract token from response
   * @return url
   */
  public function getPaymentFormToken() {
    return $this->doDealResponse->response->doDeal->token;
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
          'cardNo' => 'CGMPI',
          'creditType' => 'RegularCredit',
          'currency' => 'ILS',
          'transactionCode' => 'Phone',
          'transactionType' => 'Debit',
          'total' => $this->total,
          'validation' => 'TxnSetup',
          'mid' => $this->mid,
          'uniqueid' => $this->uniqueid,
          'mpiValidation' => 'autoComm',
          'numberOfPayments' => '1',
          'email' => $this->email,
          'description' => $this->description,
          'successUrl' => $this->successUrl,
          'errorUrl' => $this->errorUrl,
          'cancelUrl' => $this->cancelUrl
        )
      )
    );

    return $this;
  }

}
