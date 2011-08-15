<?php

class EngineBlock_Model_DbTable_GroupProviderGroupFilter extends EngineBlock_Model_DbTable_Abstract
{
   protected $_name = 'group_provider_group_filter';
   protected $_referenceMap = array(
       'GroupProvider' => array(
           'columns'       => 'group_provider_id',
           'refTableClass' => 'EngineBlock_Model_DbTable_GroupProvider',
           'refColumns'    => 'group_provider_id',
           'onDelete'      => self::CASCADE,
           'onUpdate'      => self::RESTRICT,
       )
   );
}