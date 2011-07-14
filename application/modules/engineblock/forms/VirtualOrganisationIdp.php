<?php
/**
 *
 */

class EngineBlock_Form_VirtualOrganisationIdp extends Zend_Form
{
    public function init()
    {
        $this->setName('virtualorganisationidp')
            ->setMethod('post');

        $this->_initOrgIdpId()
            ->_initVOId()
            ->_initIdpId();
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationIdp
     */
    public function _initOrgIdpId()
    {
        $element = new Zend_Form_Element_Hidden('org_idp_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationIdp
     */
    public function _initVOId()
    {
        $element = new Zend_Form_Element_Hidden('vo_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationIdp
     */
    public function _initIdpId()
    {
        $element = new Zend_Form_Element_Text('idp_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

}