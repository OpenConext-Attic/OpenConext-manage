<?php

class EngineBlock_Model_DbTable_GroupProviderDecorator extends EngineBlock_Model_DbTable_Abstract
{
   protected $_name = 'group_provider_decorator';
   protected $_dependentTables = array(
       'EngineBlock_Model_DbTable_GroupProviderDecoratorOption',
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