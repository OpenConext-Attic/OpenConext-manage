<?php

/**
 *
 */
class EngineBlock_Model_DbTable_GroupProviderDecoratorOption extends EngineBlock_Model_DbTable_Abstract {

    protected $_name = 'group_provider_decorator_option';
    protected $_referenceMap = array(
        'GroupProviderDecorator' => array(
            'columns' => 'group_provider_decorator_id',
            'refTableClass' => 'EngineBlock_Model_DbTable_GroupProviderDecorator',
            'refColumns' => 'id',
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT,
        )
    );

}
