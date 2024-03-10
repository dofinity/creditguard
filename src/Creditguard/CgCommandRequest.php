<?php

namespace Creditguard;

use Creditguard\Wsdl\ashraitTransaction;
use Creditguard\Wsdl\ashraitTransactionResponse;
use Creditguard\Wsdl\RelayService;

/**
 * Class CgCommandRequest
 * @todo Implement CgRequestInterface
 *
 * @package Creditguard
 */
class CgCommandRequest
{

  protected $rawData;

  /**
   * Required data
   */
  protected $user;
  protected $password;
  protected $mid;
  protected $terminalNumber;
  protected $relayUrl;

  /**
   * Default parameters values;
   */
  protected $version = '100';
  protected $language = 'HEB';
  protected $dateTime;
  protected $command;

  protected $doDealResponse;

  /**
   * @var array
   *   Additional information to be sent with the request
   */
  protected $extraData;

  /**
   * CgCommandRequest constructor.
   * @param $relayUrl
   * @param $user
   * @param $password
   * @param $terminalNumber
   * @param $mid
   */
  public function __construct($relayUrl, $user, $password, $terminalNumber, $mid) {
    $this->relayUrl = $relayUrl;
    $this->user = $user;
    $this->password = $password;
    $this->terminalNumber = $terminalNumber;
    $this->mid = $mid;

    $this->dateTime = date("d/m/Y H:i:s");
    $this->uniqueid = uniqid();
  }

  /**
   * Execute command with the data and fetch result
   * @return \Creditguard\CgCommandRequest
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
   * @param mixed $user
   *
   * @return CgCommandRequest
   */
  public function setUser($user) {
    $this->user = $user;
    return $this;
  }

  /**
   * @param mixed $password
   *
   * @return CgCommandRequest
   */
  public function setPassword($password) {
    $this->password = $password;
    return $this;
  }

  /**
   * @param mixed $mid
   *
   * @return CgCommandRequest
   */
  public function setMid($mid) {
    $this->mid = $mid;
    return $this;
  }

  /**
   * @param mixed $terminalNumber
   *
   * @return CgCommandRequest
   */
  public function setTerminalNumber($terminalNumber) {
    $this->terminalNumber = $terminalNumber;
    return $this;
  }

  /**
   * @return string
   */
  public function getVersion() {
    return $this->version;
  }

  /**
   * @param string $version
   *
   * @return CgCommandRequest
   */
  public function setVersion($version) {
    $this->version = $version;
    return $this;
  }

  /**
   * @param array $extraData
   *   Extra data to send to CG, should be an assoc array, e.g:
   *   [
   *      'prodcutData' => [
   *         'Product id' => '5'
   *      ]
   *   ]
   *
   * @return CgCommandRequest
   */
  public function setExtraData(array $extraData) {
    $this->extraData = $extraData;
    return $this;
  }

  /**
   * @return array
   */
  public function getResponse() {
    return !empty($this->doDealResponse->response) ? $this->doDealResponse->response : [];
  }

  /**
   * Function to build the request data as xml
   * @param $data
   * @param $xml_data
   */
  protected function buildRequestData() {
    $xml = \LSS\Array2XML::createXML('ashrait', $this->rawData);
    return $xml->saveXML();
  }
}
