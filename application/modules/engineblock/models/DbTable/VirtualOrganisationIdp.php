<?php

class EngineBlock_Model_DbTable_VirtualOrganisationIdp extends EngineBlock_Model_DbTable_Abstract
{
   protected $_name = 'virtual_organisation_idp';
   protected $_referenceMap = array(
       'VirtualOrganisation' => array(
           'columns'       => 'vo_id',
           'refTableClass' => 'EngineBlock_Model_DbTable_VirtualOrganisation',
           'refColumns'    => 'vo_id',
           'onDelete'      => self::CASCADE,
           'onUpdate'      => self::RESTRICT,
       )
   );
}