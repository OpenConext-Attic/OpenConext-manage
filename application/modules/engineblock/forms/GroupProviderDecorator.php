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
            ->_initDecoratorId()
            ->_initGroupProviderId()
            ->_initClassName()
            ->_initReplace()
            ->_initSearch();    
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
    public function _initDecoratorId()
    {
        $element = new Zend_Form_Element_Hidden('decorator_id');
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
    public function _initClassName()
    {
        $element = new Zend_Form_Element_Text('decorator_class_name');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderDecorator
     */
    public function _initReplace()
    {
        $element = new Zend_Form_Element_Text('decorator_replace');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProviderDecorator
     */
    public function _initSearch()
    {
        $element = new Zend_Form_Element_Text('decorator_search');
        return $this->addElement($element);
    }
    
}