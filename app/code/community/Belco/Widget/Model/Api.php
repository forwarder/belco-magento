<?php

/**
 * A small wrapper to communicate with the Belco API.
 * Class Belco_Hooks_Model_Api
 */
class Belco_Widget_Model_Api
{

  /**
   * @var Belco_Hooks_Model_Logger
   */
  private $logger;

  /**
   * @var Belco_Hooks_Model_BelcoCustomer
   */
  private $belcoCustomer;

  /**
   * @var Belco_Hooks_Model_BelcoOrder
   */
  private $belcoOrder;

  /**
   * Gets and sets the dependency's
   */
  public function __construct()
  {
    $this->logger = Mage::getModel('belco/logger');
    $this->belcoCustomer = Mage::getModel('belco/belcoCustomer');
    $this->belcoOrder = Mage::getModel('belco/belcoOrder');
  }

  public function connect() {
    $config = Mage::getStoreConfig('settings/general');
    $data = array(
      'id' => $config['shop_id'],
      'type' => 'magento',
      'url' => Mage::helper('core/url')->getHomeUrl()
    );
    return $this->post('shops/connect', $data);
  }

  /**
   * Synchonizes customer data to Belco
   *
   * @param $customer
   * @return mixed
   */
  public function syncCustomer($customer)
  {
    return $this->post('sync/customer', $this->toBelcoCustomer($customer));
  }

  /**
   * Synchronizes order data to Belco
   *
   * @param $order
   * @return mixed
   */
  public function syncOrder($order)
  {
    return $this->post('sync/order', $this->toBelcoOrder($order));
  }

  /**
   * Converts a Mage_Customer_Model_Customer into a simple array
   * with key/value pairs that are required by the Belco API.
   *
   * @param Mage_Customer_Model_Customer $customer
   * @return array
   */
  private function toBelcoCustomer(Mage_Customer_Model_Customer $customer)
  {
    return $this->belcoCustomer->factory($customer);
  }


  /**
   * Converts a Mage_Sales_Model_Order into a array with required key/value pairs and a
   * example details_view.
   *
   * @param Mage_Sales_Model_Order $order
   * @return array
   */
  private function toBelcoOrder(Mage_Sales_Model_Order $order)
  {
    return $this->belcoOrder->factory($order);
  }

  /**
   * Posts the values as a json string to the Belco API endpoint given in $request.
   *
   * @param $path
   * @param $data
   * @throws Exception
   * @return mixed
   */
  private function post($path, $data)
  {
    $config = Mage::getStoreConfig('settings/general');
    $errorCodes = array(500, 400, 401);
    $data = json_encode($data);
    
    if (empty($config['api_secret'])) {
      throw new Exception(
        'Missing API configuration, go to System -> Configuration -> Belco.io -> Settings and fill in your API credentials'
      );
    }

    $url = $config['api_url'] . $path;
    $this->logger->log("Posting to: " . $url);
    $this->logger->log("Data: " . $data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data),
      'X-Api-Key: ' . $config['api_secret'] 
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Dont check ssl cert
    // Todo: add cert with CURLOPT_CAINFO
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    if (curl_exec($ch) === false) {
      curl_close($ch);
      throw new Exception("Error: 'Request to Belco failed'");
    }

    $responseInfo = curl_getinfo($ch);

    curl_close($ch);

    if (in_array($responseInfo['http_code'], $errorCodes)) {
      throw new Exception("Error: 'Belco returned status code " . $responseInfo['http_code'] . "'");
    }

    $this->logger->log("Belco returned status: " . $responseInfo['http_code']);

    return $responseInfo;
  }

} 