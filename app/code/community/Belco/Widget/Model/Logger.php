<?php

/**
 * Class Belco_Widget_Model_Logger
 */
class Belco_Widget_Model_Logger {

  /**
   * Logs the given string to var/log/Belco_Widget.log
   * @param $string
   */
  public static function log($string){
    Mage::log($string, null, "Belco_Widget.log");
    if(defined('STDIN') ){
        print "Magento [Belco]: " . $string . "\n";
    }
  }

  /**
   * Logs the given string/array to var/log/Belco_Widget-debug.log
   *
   * Only use for debugging purposes! Like logging objects/arrays and
   * other information you need to check during development.
   * @param $value
   */
  public static function debug($value){
    Mage::log($value, null, "Belco_Widget-debug.log");
  }
}