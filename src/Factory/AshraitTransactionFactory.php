<?php
class AshraitTransactionFactory
{

  protected $data;
  protected $xml_data;

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
   * AshraitTransactionFactory constructor.
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
    $this->xml_data = new SimpleXMLElement('<?xml version="1.0"?><ashrait></ashrait>');
  }

  /**
   * Function to convert the request data to xml structure
   * @param $data
   * @param $xml_data
   */
  protected function generateXml($data, &$xml_data) {
    // https://stackoverflow.com/questions/1397036/how-to-convert-array-to-simplexml
    // function defination to convert array to xml
    foreach($data as $key => $value) {
      if(is_numeric($key)){
        $key = 'item'.$key; //dealing with <0/>..<n/> issues
      }
      if(is_array($value)) {
        $subnode = $xml_data->addChild($key);
        $this->generateXml($value, $subnode);
      }
      else {
        $xml_data->addChild("$key",htmlspecialchars("$value"));
      }
    }
  }

  /**
   * Converts xml structure to string
   * @return string
   */
  protected function getIntId() {
    return $this->xml_data->asXML();
  }
}