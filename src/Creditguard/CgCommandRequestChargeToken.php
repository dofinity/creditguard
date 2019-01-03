<?php

namespace Creditguard;

/**
 * Class CgCommandRequestChargeToken
 *
 * @package Creditguard
 */
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

    return $this;
  }

  /**
   * Is successful code or not.
   * Error code '000' is the success code and '179' are duplicate
   * transaction codes which also means success.
   * @todo Move to a utility class and replace here and on CgRedirectReturn
   *
   * @return bool
   */
  public function isSuccessCode() {
    $code = !empty($this->doDealResponse->response->result) ? $this->doDealResponse->response->result : FALSE;
    return in_array($code, ['000', '179']);
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
   * @todo Generalize and move parts to CgCommandRequest
   * @return $this
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
          'total' => $this->total,
          'transactionType' => 'Debit',
          'creditType' => 'RegularCredit',
          'currency' => 'ILS',
          'transactionCode' => 'Phone',
          'cardId' => $this->cardToken,
          'validation' => 'AutoComm',
          'mid' => $this->mid,
          'cardExpiration' => $this->cardExp,
          'uniqueid' => $this->uniqueid
        ]
      ]
    ];

    // Add the extraData to the request in case it exists
    if (isset($this->extraData) && is_array($this->extraData)) {
      $this->rawData['request'][$this->command] += $this->extraData;
    }

    return $this;
  }
}
