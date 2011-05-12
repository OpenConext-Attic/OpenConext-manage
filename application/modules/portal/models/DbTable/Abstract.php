<?php

abstract class Portal_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{
   protected function _setup()
   {
       $this->setOptions(array('db' => 'db_coin_portal'));
       parent::_setup();
   }
}