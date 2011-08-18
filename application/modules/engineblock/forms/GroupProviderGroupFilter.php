<?php
/**
 *
 */

class EngineBlock_Form_GroupProviderGroupFilter extends Zend_Form
{
    public function init()
    {
        $this->setName('groupprovidergroupfilter')
            ->setMethod('post');

        $this->_initOrgGroupFilterId()
            ->_initGroupFilterId()
            ->_initGroupProviderId()
            ->_initClassName()
            ->_initProperty()
            ->_initReplace()
            ->_initSearch();    
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initOrgGroupFilterId()
    {
        $element = new Zend_Form_Element_Hidden('org_group_filter_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initGroupFilterId()
    {
        $element = new Zend_Form_Element_Hidden('group_filter_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initGroupProviderId()
    {
        $element = new Zend_Form_Element_Hidden('group_provider_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }
    
    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initClassName()
    {
        $element = new Zend_Form_Element_Text('group_filter_class_name');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initProperty()
    {
        $element = new Zend_Form_Element_Text('group_filter_property');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initReplace()
    {
        $element = new Zend_Form_Element_Text('group_filter_replace');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderGroupFilter
     */
    public function _initSearch()
    {
        $element = new Zend_Form_Element_Text('group_filter_search');
        return $this->addElement($element);
    }
    
}