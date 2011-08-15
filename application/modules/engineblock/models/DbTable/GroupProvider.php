<?php
/**
 *
 */

class EngineBlock_Model_DbTable_GroupProvider extends EngineBlock_Model_DbTable_Abstract
{
    protected $_name = 'group_provider';
    protected $_dependentTables = array(
        'EngineBlock_Model_DbTable_GroupProviderDecorator',
        'EngineBlock_Model_DbTable_GroupProviderGroupFilter',
        'EngineBlock_Model_DbTable_GroupProviderGroupMemberFilter',
        'EngineBlock_Model_DbTable_GroupProviderPrecondition'
    );
}
