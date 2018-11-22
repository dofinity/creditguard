<?php

namespace Dofinity\Creditguard\Request;

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

  /**
   * Default parameters values;
   */
  protected $version = '100';
  protected $language = 'HEB';
  protected $dateTime;
  protected $command;

  /**
   * CgCommandRequest constructor.
   * @param $user
   * @param $password
   * @param $terminalNumber
   * @param $mid
   */
  public function __construct($user, $password, $terminalNumber, $mid) {
    $this->user = $user;
    $this->password = $password;
    $this->terminalNumber = $terminalNumber;
    $this->mid = $mid;

    $this->dateTime = date("d/m/Y H:i:s");
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