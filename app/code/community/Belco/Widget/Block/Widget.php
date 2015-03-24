<?php  

class Belco_Widget_Block_Widget extends Mage_Core_Block_Template {
	
	public function __construct() {
		$this->belcoCustomer = Mage::getModel('belco/belcoCustomer');
	}
	
	public function getConfig() {
		$settings = Mage::getStoreConfig('settings/general');
		$secret = $settings['api_secret'];
		$session = Mage::getSingleton('customer/session');

		$config = array(
			'shopId' => $settings['shop_id']
		);
		
		if ($session->isLoggedIn()) {
			$customer = Mage::getModel('customer/customer')->load($session->getCustomer()->getId());

			if ($secret) {
				$config['hash'] = hash_hmac("sha256", $customer->getEmail(), $secret);
			}
			$config = array_merge($config, $this->belcoCustomer->factory($customer));
		}
		
		return $config;
	}
	
}