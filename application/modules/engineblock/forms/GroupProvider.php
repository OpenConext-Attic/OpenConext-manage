<?php
/**
 *
 */

class EngineBlock_Form_GroupProvider extends Zend_Form
{
    public function init()
    {
        $this->setName('groupprovider')
            ->setMethod('post');

        $this->_initId()
            ->_initType()
            ->_initClassName();
    }

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initId()
    {
        $element = new Zend_Form_Element_Text('group_provider_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initType()
    {
        $element = new Zend_Form_Element_Radio('group_provider_type');
        $element->addMultiOptions(array(
            'GROUPER' => 'Grouper',
            'OAUTH' => 'Oauth',
        ));
        $element->setRequired(true);
        $element->setAllowEmpty(false);
        $element->setValue('GROUPER');
        return $this->addElement($element);
    }

    public function _initClassName()
    {
        $element = new Zend_Form_Element_Text('class_name');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }
    
}