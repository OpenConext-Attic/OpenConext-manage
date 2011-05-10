<?php

abstract class Model_Dao_Abstract extends Zend_Db_Table_Abstract
{
   protected $_use_adapter = '';

   protected function _setup()
   {
       if (!empty($this->_use_adapter)) {
           $this->setOptions(array('db' => $this->_use_adapter));
       }

       parent::_setup();
   }
}