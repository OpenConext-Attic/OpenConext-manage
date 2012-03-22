<?php
/**
 *
 */

class EngineBlock_Model_ServiceProviderGroupAcl extends Default_Model_Abstract
{
    public $id;
    public $groupProviderId;
    public $spentityid;
    public $allow_groups = false;
    public $allow_members = false;
}