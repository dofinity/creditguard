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

  protected $validation_errors;

  /**
   * CgRedirectReturn constructor.
   * @param array $queryParams
   *   Associative array of all parameters received from CG
   */
  public function __construct($queryParams) {
    // Init the main parameters
    $this->errorCode = !empty($_GET['ErrorCode']) ? $_GET['ErrorCode'] : '000';
    $this->txId = !empty($_GET['txId']) ? $_GET['txId'] : NULL;
    $this->cardExp = !empty($_GET['cardExp']) ? $_GET['cardExp'] : NULL;
    $this->authNum = !empty($_GET['authNumber']) ? $_GET['authNumber'] : NULL;
    $this->cardMask = !empty($_GET['cardMask']) ? $_GET['cardMask'] : NULL;
    $this->errorText = !empty($_GET['ErrorText']) ? htmlspecialchars($_GET['ErrorText'], ENT_QUOTES, 'UTF-8') : '';

    $this->checkParamsValidity();
  }

  /**
   * Shva code
   * @return string
   */
  public function getErrorCode() {
    return $this->isParamValid('errorCode') ? $this->errorCode : FALSE;
  }

  /**
   * @return string
   */
  public function getTxId() {
    return $this->isParamValid('txId') ? $this->txId : FALSE;
  }

  /**
   * @return string
   */
  public function getCardExp() {
    return $this->isParamValid('cardExp') ?  $this->cardExp : FALSE;
  }

  /**
   * @return string
   */
  public function getAuthNum() {
    return $this->isParamValid('authNum') ?  $this->authNum : FALSE;
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
    return $this->isParamValid('cardMask') ? substr($this->cardMask, -4) : FALSE;
  }

  /**
   * Is successful code or not.
   * Error code '000' is the success code and '179' / '-80' are duplicate
   * transaction codes which also means success.
   *
   * @return bool
   */
  public function isSuccessCode() {
    return in_array($this->getErrorCode(), ['000', '179', '-80']);
  }

  /**
   * Check if the given param name has validation errors
   * @param $paramName
   *
   * @return bool
   *   True for valid, false for invalid
   */
  public function isParamValid($paramName) {
    return !key_exists($paramName, $this->validation_errors);
  }

  /**
   * Array of validation messages where the key is the param name
   * @return array
   *   Messages or empty array in case of no validation errors
   */
  public function getValidationErrors() {
    return $this->validation_errors;
  }

  /**
   * Helper function to check if the parameters are safe to use
   *
   * @return array
   *   Messages or empty array in case of no validation errors
   */
  private function checkParamsValidity() {
    $this->validation_errors = [];

    // Check for required fields
    foreach ($this->getRequiredParamNames() as $required_field) {
      if (empty($this->{$required_field})) {
        $this->validation_errors[$required_field] = "Required field is missing $this->{$required_field} query parameter";
      }
    }

    // Check the numeric fields
    foreach ($this->getNumericParamNames() as $numeric_param_name) {
      $value = $this->{$numeric_param_name};
      if (!empty($value) && !is_numeric($value)) {
        $this->validation_errors[$numeric_param_name] = "Illegal value in $this->{$numeric_param_name} query parameter";
      }
    }

    // Check the card mask (last 4 digits)
    if (!is_numeric($this->getLastDigits())) {
      $this->validation_errors['cardMask'] = "Illegal value in cardMask query parameter";
    }

    // Validate the transaction ID
    if (!preg_match('/^[a-zA-Z0-9\-]*$/', $this->txId)) {
      $this->validation_errors['txId'] = "Illegal value in txId query parameter";
    }
  }

  /**
   * @return array
   */
  private function getRequiredParamNames() {
    return [
      'txId',
      'cardMask',
      'cardExp'
    ];
  }

  /**
   * @return array
   */
  private function getNumericParamNames() {
    return [
      'errorCode',
      'cardExp',
      'authNumber'
    ];
  }
}
