<?php
/**
 *
 */

class EngineBlock_Form_EmailConfiguration extends Zend_Form
{
    public function init()
    {
        $this->setName('emailconfiguration')
            ->setMethod('post');

        $this->_initId()
            ->_initEmailType()
            ->_initEmailFrom()
            ->_initEmailSubject()
            ->_initEmailText();
    }

    /**
     * @return EngineBlock_Form_EmailConfiguration
     */
    public function _initId()
    {
        $element = new Zend_Form_Element_Text('id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_EmailConfiguration
     */
    public function _initEmailType()
    {
        $element = new Zend_Form_Element_Text('email_type');
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_EmailConfiguration
     */
    public function _initEmailFrom()
    {
        $element = new Zend_Form_Element_Text('email_from');
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_EmailConfiguration
     */
    public function _initEmailSubject()
    {
        $element = new Zend_Form_Element_Text('email_subject');
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }
    /**
     * @return EngineBlock_Form_EmailConfiguration
     */
    public function _initEmailText()
    {
        $element = new Zend_Form_Element_Textarea('email_text');
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }
}