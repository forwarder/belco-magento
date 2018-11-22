<?php

class Belco_Widget_Block_Widget extends Mage_Core_Block_Template {

  const DATA_TAG = "belco_events";

  public function __construct() {
    $this->belcoCustomer = Mage::getModel('belco/belcoCustomer');
  }

  public function getConfig() {
    $settings = Mage::getStoreConfig('belco_settings/general', Mage::app()->getStore());
    $secret = $settings['api_secret'];
    $session = Mage::getSingleton('customer/session');

    $config = array(
      'shopId' => $settings['shop_id']
    );

    if ($session->isLoggedIn()) {
      $customer = Mage::getModel('customer/customer')->load($session->getCustomer()->getId(), $secret);

      if ($secret) {
        $config['hash'] = hash_hmac("sha256", $customer->getId(), $secret);
      }
      $config = array_merge($config, $this->belcoCustomer->factory($customer));
      } else {
      $events = $this->getEvents();
      $config['hash'] = hash_hmac('sha256', $customer['email'], $secret);
      $event = array_shift($events);

      if ($event && $event['method'] === 'identify') {
        if ($secret) {
          $config['hash'] = hash_hmac('sha256', $event['data']['email'], $secret);
        }
        $config = array_merge($config, $event['data']);
      }
    }

    if ($cart = $this->getCart()) {
      $config['cart'] = $cart;
    }

    return $config;
  }

  public function getEvents() {
    $events = (array)Mage::getSingleton('core/session')->getData(self::DATA_TAG);

    Mage::getSingleton('core/session')->setData(self::DATA_TAG, '');

    return array_filter($events);
  }

  protected function getCart() {
    $cart = Mage::helper('checkout/cart')->getCart();
    $quote = $cart->getQuote();
    $items = $quote->getAllVisibleItems();

    $config = array(
      'items' => array(),
      'total' => $quote->getGrandTotal()
    );

    foreach ($items as $item) {
      $product = $item->getProduct();

      $config['items'][] = array(
        'id' => $product->getId(),
        'quantity' => $item->getQty(),
        'name' => $item->getName(),
        'price' => $item->getPrice(),
        'url' => $product->getProductUrl()
      );
    }

    if (count($config['items'])) {
      return $config;
    }
  }

}
