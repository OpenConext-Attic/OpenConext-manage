<?php
/**
 *
 */

class Portal_Form_GadgetDefinition extends Zend_Form
{
    public function init()
    {
        $this->setName('gadgetdefinition')
            ->setMethod('post');

        $this->_initUrl()
            ->_initAuthorName()
            ->_initAuthorEmail()
            ->_initTitle()
            ->_initDescription()
            ->_initScreenShotUrl()
            ->_initThumbnailUrl()
            ->_initSupportsGroups()
            ->_initSupportsSingleSignOn()
			->_initFixedTabGadget()
            ->_initIsCustom();
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initUrl()
    {
        $element = new Zend_Form_Element_Text('url');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initAuthorName()
    {
        $element = new Zend_Form_Element_Text('authorName');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initAuthorEmail()
    {
        $element = new Zend_Form_Element_Text('authorEmail');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initTitle()
    {
        $element = new Zend_Form_Element_Text('title');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initDescription()
    {
        $element = new Zend_Form_Element_Textarea('description');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initScreenShotUrl()
    {
        $element = new Zend_Form_Element_Text('screenShotUrl');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(FALSE);
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initThumbnailUrl()
    {
        $element = new Zend_Form_Element_Text('thumbnailUrl');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initSupportsGroups()
    {
        $element = new Zend_Form_Element_Checkbox('supportsGroups');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initSupportsSingleSignOn()
    {
        $element = new Zend_Form_Element_Checkbox('supportsSingleSignOn');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initFixedTabGadget()
    {
        $element = new Zend_Form_Element_Checkbox('fixedTabGadget');
        return $this->addElement($element);
    }

    /**
     * @return Portal_Form_GadgetDefinition
     */
    public function _initIsCustom()
    {
        $element = new Zend_Form_Element_Checkbox('isCustom');
        return $this->addElement($element);
    }
}