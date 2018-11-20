<?php
class AshraitTransactionFactoryDoDeal extends AshraitTransactionFactory
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
   * AshraitTransactionFactoryDoDeal constructor.
   * @param $user
   * @param $password
   * @param $terminalNumber
   * @param $mid
   * @param $total
   * @param $email
   * @param $description
   */
  public function __construct($user, $password, $terminalNumber, $mid) {
    parent::__construct($user, $password, $terminalNumber, $mid);
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
   * @return SimpleXMLElement
   */
  public function execute() {
    $this->makeDataArray();
    $this->generateXml($this->data, $this->xml_data);
    $int_id = $this->getIntId();

    $ashrait = new ashraitTransaction($this->user, $this->password, $int_id);
    $rs = new RelayService();
    $result = new ashraitTransactionResponse($rs->ashraitTransaction($ashrait));

    // We are calling getAshraitTransactionReturn() twice becuase CG returns object in an object for some reason
    $raw_result = $result->getAshraitTransactionReturn()->getAshraitTransactionReturn();
    $raw_result = mb_convert_encoding($raw_result, 'ISO-8859-8', 'UTF-8');
    $this->doDealResponse = simplexml_load_string($raw_result);

    return $this;
  }

  /**
   * Extract redirect url from response
   * @return url
   */
  public function getRedirectUrl() {
    return $this->doDealResponse->response->doDeal->mpiHostedPageUrl;
  }

  /**
   * Extract token from response
   * @return url
   */
  public function getToken() {
    return $this->doDealResponse->response->doDeal->token;
  }

  /**
   * Creating the data array
   * @return $this
   */
  protected function makeDataArray() {
    $this->data = array(
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