<?php
/**
 *
 */

class EngineBlock_Form_GroupProviderDecorator extends Zend_Form
{
    public function init()
    {
        $this->setName('groupproviderdecorator')
            ->setMethod('post');

        $this->_initOrgDecoratorId()
            ->_initGroupProviderId()
            ->_initDecoratorId();
    }

    /**
     * @return EngineBlock_Form_GroupProviderDecorator
     */
    public function _initOrgDecoratorId()
    {
        $element = new Zend_Form_Element_Hidden('org_decorator_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderDecorator
     */
    public function _initGroupProviderId()
    {
        $element = new Zend_Form_Element_Hidden('group_provider_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderDecorator
     */
    public function _initDecoratorId()
    {
        $element = new Zend_Form_Element_Text('decorator_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

}