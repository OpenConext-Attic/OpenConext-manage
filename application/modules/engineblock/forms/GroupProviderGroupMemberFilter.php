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
            ->_initGroupMemberFilterId()
            ->_initGroupProviderId()
            ->_initClassName()
            ->_initProperty()
            ->_initReplace()
            ->_initSearch();    
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initOrgGroupMemberFilterId()
    {
        $element = new Zend_Form_Element_Hidden('org_group_member_filter_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initGroupMemberFilterId()
    {
        $element = new Zend_Form_Element_Hidden('group_member_filter_id');
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
    public function _initClassName()
    {
        $element = new Zend_Form_Element_Text('group_member_filter_class_name');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initProperty()
    {
        $element = new Zend_Form_Element_Text('group_member_filter_property');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initReplace()
    {
        $element = new Zend_Form_Element_Text('group_member_filter_replace');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupMemberFilter
     */
    public function _initSearch()
    {
        $element = new Zend_Form_Element_Text('group_member_filter_search');
        return $this->addElement($element);
    }
    
}