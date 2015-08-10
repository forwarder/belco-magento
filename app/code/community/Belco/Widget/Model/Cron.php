<?php


/**
 * Class Belco_Widget_Model_Cron
 */
class Belco_Widget_Model_Cron
{

    protected function _syncCustomer($customerId)
    {
        $customer = Mage::getModel('customer/customer')->load($customerId);
        try {
            $this->api->syncCustomer($customer);
        } catch (Exception $e) {
            $this->helper->log("Exception: " . $e->getMessage());
            $this->helper->warnAdmin($e->getMessage());
        }
    }
}