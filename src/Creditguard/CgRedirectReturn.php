<?php

namespace Creditguard;

/**
 * Class CgRedirectReturn
 *   Responsible of handling request from the client after returning from CG
 *
 * @package Creditguard
 */
class CgRedirectReturn {

  protected $errorCode;
  protected $txId;
  protected $cardExp;
  protected $authNum;
  protected $cardMask;
  protected $errorText;

  /**
   * CgRedirectReturn constructor.
   * @param array $queryParams
   *   Associative array of all parameters received from CG
   *
   * @return array
   *   Returns array of validation error messages or empty array if non
   */
  public function __construct($queryParams) {
    // Init the main parameters
    $this->errorCode = !empty($_GET['ErrorCode']) ? $_GET['ErrorCode'] : '000';
    $this->txId = !empty($_GET['txId']) ? $_GET['txId'] : NULL;
    $this->cardExp = !empty($_GET['cardExp']) ? $_GET['cardExp'] : NULL;
    $this->authNum = !empty($_GET['authNumber']) ? $_GET['authNumber'] : NULL;
    $this->cardMask = !empty($_GET['cardMask']) ? $_GET['cardMask'] : NULL;
    $this->errorText = !empty($_GET['ErrorText']) ? $_GET['ErrorText'] : '';

    return $this->isValidParams();
  }

  /**
   * @return string
   */
  public function getErrorCode() {
    return $this->errorCode;
  }

  /**
   * @return string
   */
  public function getTxId() {
    return $this->txId;
  }

  /**
   * @return string
   */
  public function getCardExp() {
    return $this->cardExp;
  }

  /**
   * @return string
   */
  public function getAuthNum() {
    return $this->authNum;
  }

  /**
   * @return string
   */
  public function getErrorText() {
    return $this->errorText;
  }

  /**
   * Returns the last 4 digits of the credit card
   * @return bool|string
   */
  public function getLastDigits() {
    return substr($this->cardMask, -4);
  }

  /**
   * Helper function to check if the parameters are safe to use
   *
   * @return array
   *   Messages or empty array in case of no validation erros
   */
  public function isValidParams() {
    $messages = [];
    $required_fields = [
      'txId',
      'cardMask',
      'cardExp'
    ];

    $numeric_fields = [
      'errorCode',
      'cardExp',
      'authNumber'
    ];

    // Check for required fields
    foreach ($required_fields as $required_field) {
      if (empty($this->{$required_field})) {
        $messages[] = "Required field is missing $this->{$required_field} query parameter";
      }
    }

    // Check the numeric fields
    foreach ($numeric_fields as $numeric_field) {
      $value = $this->{$numeric_field};
      if (!empty($value) && !is_numeric($value)) {
        $messages[] = "Illegal value in $this->{$numeric_field} query parameter";
      }
    }

    // Check the card mask (last 4 digits)
    if (!is_numeric($this->getLastDigits())) {
      $messages[] = "Illegal value in cardMask query parameter";
    }

    // Validate the transaction ID
    if (!preg_match('/^[a-zA-Z0-9\-]*$/', $this->txId)) {
      $messages[] = "Illegal value in txId query parameter";
    }

    return $messages;
  }
}
