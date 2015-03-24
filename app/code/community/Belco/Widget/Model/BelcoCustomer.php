<?php
class Belco_Widget_Model_BelcoCustomer {

    /**
     * @var Mage_Customer_Model_Customer
     */
    private $customer;

    /**
     * Factory method for creating an array with key/value pairs
     * the Belco API expects.
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return array
     */
    public function factory(Mage_Customer_Model_Customer $customer){
        $this->customer = $customer;
        return $this->make();
    }

    /**
     * Makes the array the Belco API expects.
     *
     * @return array
     */
    private function make(){
        $lifetime = $this->getLifeTimeSalesCustomer();

        $belcoCustomer = array(
            'email' => $this->customer->getEmail(),
			'name' => $this->customer->getName(),
            'signedUp' => strtotime($this->customer->getCreatedAt()),
            'orders' => $lifetime->getNumOrders(),
            'totalSpent' => $lifetime->getLifetime()
        );
		
		$address = $this->customer->getDefaultBillingAddress();
		
		if (!empty($address)) {
			$belcoCustomer = array_merge($belcoCustomer, array(
				'phoneNumber' => $address->getTelephone(),
				'country' => $address->getCountry(),
				'city' => $address->getCity()
			));
		}

        return $belcoCustomer;
    }

    /**
     * Gets customer statics like total order count and total spend.
     *
     * @return array
     */
    function getLifeTimeSalesCustomer(){
        return Mage::getResourceModel('sales/sale_collection')
			->setOrderStateFilter(null)
            ->setCustomerFilter($this->customer)
            ->load()
            ->getTotals();
    }
} 