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
        $this->setData('type', empty($params['type']) ? 'unknown' : $params['type']);
        $this->setData('data', json_encode((array)$params['data']));
        $this->setData('created_at', time());

        try {
            $this->save();
            return true;
        } catch(Exception $e) {
            $this->logger->log("Queue exception: " . $e->getMessage());
            return false;
        }
    }
}