<?php

class Belco_Widget_Block_Widget extends Mage_Core_Block_Template {

  public function __construct() {
    $this->belcoCustomer = Mage::getModel('belco/belcoCustomer');
  }

  public function getConfig() {
    $settings = Mage::getStoreConfig('belco_settings/general');
    $secret = $settings['api_secret'];
    $session = Mage::getSingleton('customer/session');

    $config = array(
      'shopId' => $settings['shop_id']
    );

    if ($session->isLoggedIn()) {
      $customer = Mage::getModel('customer/customer')->load($session->getCustomer()->getId());

      if ($secret) {
        $config['hash'] = hash_hmac("sha256", $customer->getId(), $secret);
      }
      $config = array_merge($config, $this->belcoCustomer->factory($customer));
    }

    if ($cart = $this->getCart()) {
      $config['cart'] = $cart;
    }

    return $config;
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
