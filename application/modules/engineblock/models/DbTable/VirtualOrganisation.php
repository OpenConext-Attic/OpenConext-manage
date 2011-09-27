<?php

class EngineBlock_Model_DbTable_VirtualOrganisation extends EngineBlock_Model_DbTable_Abstract
{
   protected $_name = 'virtual_organisation';
   protected $_dependentTables = array(
       'EngineBlock_Model_DbTable_VirtualOrganisationGroup',
       'EngineBlock_Model_DbTable_VirtualOrganisationIdp'
   );
}