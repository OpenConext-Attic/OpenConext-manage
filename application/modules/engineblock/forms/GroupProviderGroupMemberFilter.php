<?php
/**
 *
 */

class EngineBlock_Form_GroupProviderGroupMemberFilter extends Zend_Form
{
    public function init()
    {
        $this->setName('groupprovidergroupmemberfilter')
            ->setMethod('post');

        $this->_initOrgGroupMemberFilterId()
            ->_initGroupProviderId()
            ->_initGroupMemberFilterId();
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initOrgGroupMemberFilterId()
    {
        $element = new Zend_Form_Element_Hidden('org_groupmemberfilter_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initGroupProviderId()
    {
        $element = new Zend_Form_Element_Hidden('group_provider_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initGroupMemberFilterId()
    {
        $element = new Zend_Form_Element_Text('groupmemberfilter_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

}