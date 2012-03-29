<?php
/**
 *
 */

class EngineBlock_Form_VirtualOrganisation extends Zend_Form
{
    public function init()
    {
        $this->setName('virtualorganisation')
                ->setMethod('post');

        $this->_initOrgVOId()
                ->_initVOId()
                ->_initVOType();
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisation
     */
    public function _initOrgVOId()
    {
        $element = new Zend_Form_Element_Hidden('org_vo_id');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_VirtualOrganisation
     */
    public function _initVOId()
    {
        $element = new Zend_Form_Element_Text('vo_id');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        $validator = new Zend_Validate_Regex("/^[a-zA-Z0-9\-_]+$/");
        $element->addValidator($validator);
        $element->addErrorMessage("Illegal characters detected.");
        return $this->addElement($element);
    }

    public function _initVOType()
    {
        $element = new Zend_Form_Element_Radio('vo_type');
        $element->addMultiOptions(array(
            'MIXED' => 'Mixed',
            'GROUP' => 'Group',
            'STEM'  => 'Stem',
            'IDP'   => 'IdP',
        ));
        $element->setRequired(true);
        $element->setAllowEmpty(false);
        $element->setValue('MIXED');
        return $this->addElement($element);
    }

}