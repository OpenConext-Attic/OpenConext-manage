<?php
/**
 *
 */

class Portal_Form_TextContent extends Zend_Form
{
    public function init()
    {
        $this->setName('textcontent')
            ->setMethod('post');

        $this->_initId()
            ->_initField()
            ->_initText();
    }

    /**
     * @return Portal_Form_TextContent
     */
    public function _initId()
    {
        $element = new Zend_Form_Element_Text('id');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_TextContent
     */
    public function _initField()
    {
        $element = new Zend_Form_Element_Text('field');
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_TextContent
     */
    public function _initText()
    {
        $element = new Zend_Form_Element_Textarea('text');
        return $this->addElement($element);
    }
}