<?php

class Belco_Widget_Model_Resource_Queue extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('belco/queue', 'queue_id');
    }
}