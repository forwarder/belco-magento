<?php


/**
 * Class Belco_Widget_Model_Cron
 */
class Belco_Widget_Model_Cron
{

    /**
     * @var Belco_Widget_Model_Api
     */
    private $api;

    /**
     * @var Belco_Widget_Helper_Data
     */
    private $helper;
    /**
     * Gets and sets the dependency's
     */
    public function __construct(){
        $this->helper = Mage::helper("belco");
        $this->api = $this->helper->getApi();
    }

    public function processQueue()
    {
        $_jobsCollection = Mage::getModel('belco/queue')->getCollection()
            ->addFieldToSelect(array('queue_id', 'type', 'data', 'created_at'))
            ->addFieldToFilter('processed_at', array('null' => true))
            ->setOrder('created_at','ASC');

        foreach ($_jobsCollection as $_job) {
            $result = false;
            $data = json_decode((string)$_job->getData('data'), true);

            switch ($_job->getData('type')) {
                case 'customer':
                    $result = $this->_syncCustomer($data); break;
                case 'order':
                    $result = $this->_syncOrder($data); break;
            }

            if ($result === true) {
                $_job->setData('processed_at', time());
                try {
                    $_job->save();
                } catch(Exception $e) {
                    $this->helper->log("Exception: " . $e->getMessage());
                }
            } else {
                $this->helper->log("Queue job error: " . $result);
            }
        }
    }

    protected function _syncCustomer($data)
    {
        $customer = Mage::getModel('customer/customer')->load($data['customer_id']);
        try {
            $this->api->syncCustomer($customer);
        } catch (Exception $e) {
            $this->helper->log("Exception: " . $e->getMessage());
            return $e;
        }

        return true;
    }

    protected function _syncOrder($data)
    {
        $order = Mage::getModel('sales/order')->load($data['order_id']);
        try{
            $this->api->syncOrder($order);
        }
        catch(Exception $e){
            $this->helper->log("Exception: ". $e->getMessage());
            return $e;
        }

        return true;
    }
}