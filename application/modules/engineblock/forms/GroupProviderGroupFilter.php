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
            ->_initGroupProviderId()
            ->_initGroupFilterId();
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
    public function _initGroupFilterId()
    {
        $element = new Zend_Form_Element_Text('group_filter_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

}