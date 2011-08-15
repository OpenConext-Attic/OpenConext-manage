<?php
/**
 *
 */

class EngineBlock_Form_GroupProviderPrecondition extends Zend_Form
{
    public function init()
    {
        $this->setName('groupproviderprecondition')
            ->setMethod('post');

        $this->_initOrgPreconditionId()
            ->_initGroupProviderId()
            ->_initPreconditionId();
    }

    /**
     * @return EngineBlock_Form_GroupProviderPrecondition
     */
    public function _initOrgPreconditionId()
    {
        $element = new Zend_Form_Element_Hidden('org_precondition_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderPrecondition
     */
    public function _initGroupProviderId()
    {
        $element = new Zend_Form_Element_Hidden('group_provider_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderPrecondition
     */
    public function _initPreconditionId()
    {
        $element = new Zend_Form_Element_Text('precondition_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

}