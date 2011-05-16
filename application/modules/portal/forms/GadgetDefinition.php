<?php
/**
 *
 */

class Portal_Form_GadgetDefinition extends Zend_Form
{
    public function init()
    {
        $this->setName('gadgetdefinition')
            ->setMethod('post')
            ->_initUrl()
            ->_initAuthorName()
            ->_initAuthorEmail()
            ->_initTitle()
            ->_initDescription()
            ->_initScreenShotUrl()
            ->_initThumbnailUrl()
            ->_initSupportsGroups()
            ->_initSupportsSingleSignOn()
            ->_initIsCustom();
    }

    public function _initUrl()
    {
        $element = new Zend_Form_Element_Text('url');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initAuthorName()
    {
        $element = new Zend_Form_Element_Text('authorName');
        return $this->addElement($element);
    }

    public function _initAuthorEmail()
    {
        $element = new Zend_Form_Element_Text('authorEmail');
        return $this->addElement($element);
    }

    public function _initTitle()
    {
        $element = new Zend_Form_Element_Text('title');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initDescription()
    {
        $element = new Zend_Form_Element_Textarea('description');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initScreenShotUrl()
    {
        $element = new Zend_Form_Element_Text('screenShotUrl');
        return $this->addElement($element);
    }

    public function _initThumbnailUrl()
    {
        $element = new Zend_Form_Element_Text('thumbnailUrl');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initSupportsGroups()
    {
        $element = new Zend_Form_Element_Checkbox('supportsGroups');
        return $this->addElement($element);
    }

    public function _initSupportsSingleSignOn()
    {
        $element = new Zend_Form_Element_Checkbox('supportsSingleSignOn');
        return $this->addElement($element);
    }

    public function _initIsCustom()
    {
        $element = new Zend_Form_Element_Checkbox('isCustom');
        return $this->addElement($element);
    }
}