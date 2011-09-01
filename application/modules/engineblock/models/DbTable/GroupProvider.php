<?php
/**
 *
 */

class EngineBlock_Model_DbTable_GroupProvider extends EngineBlock_Model_DbTable_Abstract
{
    protected $_name = 'group_provider';
    protected $_dependentTables = array(
        'EngineBlock_Model_DbTable_GroupProviderOption',
        'EngineBlock_Model_DbTable_GroupProviderPrecondition',
        'EngineBlock_Model_DbTable_GroupProviderDecorator',
        'EngineBlock_Model_DbTable_GroupProviderFilter',
    );
}
