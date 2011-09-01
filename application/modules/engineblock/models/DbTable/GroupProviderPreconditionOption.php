<?php

/**
 *
 */
class EngineBlock_Model_DbTable_GroupProviderPreconditionOption extends EngineBlock_Model_DbTable_Abstract {

    protected $_name = 'group_provider_precondition_option';
    protected $_referenceMap = array(
        'GroupProviderPrecondition' => array(
            'columns' => 'group_provider_precondition_id',
            'refTableClass' => 'EngineBlock_Model_DbTable_GroupProviderPrecondition',
            'refColumns' => 'id',
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT,
        )
    );

}
