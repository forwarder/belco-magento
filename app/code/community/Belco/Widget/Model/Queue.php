<?php

class Belco_Widget_Model_Queue extends Mage_Core_Model_Abstract
{

    /**
     * @var Belco_Hooks_Model_Logger
     */
    private $logger;

    /**
     * Gets and sets the dependency's
     */
    public function __construct()
    {
        $this->logger = Mage::getModel('belco/logger');
        $this->_init('belco/queue');
    }

    public function addJob($params)
    {
        $this->addData('type', empty($params['type']) ? 'unknown' : $params['type']);
        $this->addData('data', json_encode((array)$params['type']));
        $this->addData('created_at', time());

        try {
            $this->save();
            return true;
        } catch(Exception $e) {
            $this->helper->log("Queue exception: " . $e->getMessage());
            return false;
        }
    }
}