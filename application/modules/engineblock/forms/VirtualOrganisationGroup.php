<?php
/**
 *
 */

class EngineBlock_Form_VirtualOrganisationGroup extends Zend_Form
{
    public function init()
    {
        $this->setName('virtualorganisationgroup')
            ->setMethod('post');

        $this->_initOrgGroupId()
            ->_initVOId()
            ->_initGroupId()
            ->_initGroupStem();
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationGroup
     */
    public function _initOrgGroupId()
    {
        $element = new Zend_Form_Element_Hidden('org_group_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationGroup
     */
    public function _initVOId()
    {
        $element = new Zend_Form_Element_Hidden('vo_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationGroup
     */
    public function _initGroupId()
    {
        $element = new Zend_Form_Element_Text('group_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisationGroup
     */
    public function _initGroupStem()
    {
        $element = new Zend_Form_Element_Text('group_stem');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

}