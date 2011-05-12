<?php

abstract class ServiceRegistry_Model_DbTable_Abstract extends Zend_Db_Table_Abstract
{
    const DATABASE_CONFIG_KEY = 'db_service_registry';

    protected function _setup()
    {
       $this->setOptions(array('db' => self::DATABASE_CONFIG_KEY));
       parent::_setup();
    }
}