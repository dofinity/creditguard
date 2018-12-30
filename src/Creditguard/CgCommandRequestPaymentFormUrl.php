<?php

namespace Creditguard;

/**
 * Class CgCommandRequestPaymentFormUrl
 *
 * @package Creditguard
 */
class CgCommandRequestPaymentFormUrl extends CgCommandRequest {

  protected $email;

  protected $description;

  protected $total;

  protected $successUrl = "";

  protected $errorUrl = "";

  protected $cancelUrl = "";

  protected $uniqueid;

  /**
   * CgCommandRequestPaymentFormUrl constructor.
   *
   * @param $relayUrl
   * @param $user
   * @param $password
   * @param $terminalNumber
   * @param $mid
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function __construct($relayUrl, $user, $password, $terminalNumber, $mid) {
    parent::__construct($relayUrl, $user, $password, $terminalNumber, $mid);
    $this->command = 'doDeal';

    return $this;
  }

  /**
   * @param mixed $email
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }

  /**
   * @param string $description
   *   Should be in the values range of 0-9, a-z, A-Z and special charecters
   *   like: _ - : and space
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function setDescription($description) {
    // Filter all illegal chars as described on CG's errors
    $allowed_pattern = "/[^a-zA-Zא-תF0-9_:\- ]/";
    $this->description = preg_replace($allowed_pattern, "", $description);
    return $this;
  }

  /**
   * @param float $total
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function setTotal($total) {
    $this->total = $total;
    return $this;
  }

  /**
   * @param string $successUrl
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function setSuccessUrl($successUrl) {
    $this->successUrl = $successUrl;
    return $this;
  }

  /**
   * @param string $errorUrl
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function setErrorUrl($errorUrl) {
    $this->errorUrl = $errorUrl;
    return $this;
  }

  /**
   * @param string $cancelUrl
   *
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  public function setCancelUrl($cancelUrl) {
    $this->cancelUrl = $cancelUrl;
    return $this;
  }

  /**
   * Extract form url from response
   *
   * @return string
   */
  public function getPaymentFormUrl() {
    return $this->doDealResponse->response->doDeal->mpiHostedPageUrl->__toString();
  }

  /**
   * Extract token from response
   *
   * @return string
   */
  public function getPaymentFormToken() {
    return $this->doDealResponse->response->doDeal->token;
  }

  /**
   * Creating the data array
   *
   * @todo Generalize and move parts to CgCommandRequest
   * @return \Creditguard\CgCommandRequestPaymentFormUrl $this
   */
  protected function prepareRequestData() {
    $this->rawData = [
      'request' => [
        'version' => $this->version,
        'language' => $this->language,
        'dateTime' => $this->dateTime,
        'command' => $this->command,
        $this->command => [
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
          'mpiValidation' => 'Token',
          'email' => $this->email,
          'description' => $this->description,
          'successUrl' => $this->successUrl,
          'errorUrl' => $this->errorUrl,
          'cancelUrl' => $this->cancelUrl,
        ],
      ],
    ];

    return $this;
  }

}
