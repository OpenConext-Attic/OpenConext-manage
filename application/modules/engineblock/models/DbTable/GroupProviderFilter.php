<?php

class EngineBlock_Model_DbTable_GroupProviderFilter extends EngineBlock_Model_DbTable_Abstract
{
   protected $_name = 'group_provider_filter';
   protected $_dependentTables = array(
       'EngineBlock_Model_DbTable_GroupProviderFilterOption',
   );
   protected $_referenceMap = array(
       'GroupProvider' => array(
           'columns'       => 'group_provider_id',
           'refTableClass' => 'EngineBlock_Model_DbTable_GroupProvider',
           'refColumns'    => 'id',
           'onDelete'      => self::CASCADE,
           'onUpdate'      => self::RESTRICT,
       )
   );
}