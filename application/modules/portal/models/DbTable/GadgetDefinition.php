<?php

class Portal_Model_DbTable_GadgetDefinition extends Portal_Model_DbTable_Abstract
{
   protected $_name = 'gadgetdefinition';
   protected $_dependentTables = array('Portal_Model_DbTable_Gadget');
}