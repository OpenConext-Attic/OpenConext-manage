<?php

class Portal_Model_DbTable_Gadget extends Portal_Model_DbTable_Abstract
{
    protected $_name = 'gadget';
    protected $_referenceMap = array (
        'GadgetDefinition' => array (
            'columns' => array ('definition_id'),
            'refTableClass' => 'Portal_Model_DbTable_GadgetDefinition',
            'refColumns' => array ('id'),
        ),
    );
}