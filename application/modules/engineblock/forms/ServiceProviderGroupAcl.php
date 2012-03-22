<?php
/**
 *
 */

class EngineBlock_Form_ServiceProviderGroupAcl extends Zend_Form
{
    public function init()
    {
        $this->setName('serviceprovidergroupacl')
            ->setMethod('post');
    }

}