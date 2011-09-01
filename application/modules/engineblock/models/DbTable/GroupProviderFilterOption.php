<?php

/**
 *
 */
class EngineBlock_Model_DbTable_GroupProviderFilterOption extends EngineBlock_Model_DbTable_Abstract {

    protected $_name = 'group_provider_filter_option';
    protected $_referenceMap = array(
        'GroupProviderFilter' => array(
            'columns' => 'group_provider_filter_id',
            'refTableClass' => 'EngineBlock_Model_DbTable_GroupProviderFilter',
            'refColumns' => 'id',
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT,
        )
    );

}
