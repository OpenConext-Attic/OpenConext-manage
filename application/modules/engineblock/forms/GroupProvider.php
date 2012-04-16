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
            ->_initIdentifier()
            ->_initName()
            ->_initClassName()
            ->_initLogoUrl();
        
    }

    public function isValid($data) {
        if (isset($data['classname'])) {
            switch ($data['classname']) {
                case 'GROUPER':
                    $this->_initURL()
                        ->_initUsername()
                        ->_initPassword();
                    break;
                case 'OPENSOCIAL_BASIC':
                    break;
                case 'OPENSOCIAL_OAUTH':
                    break;
                default:
                    break;
            }
        }
        return parent::isValid($data);
    }
    
    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initId()
    {
        $element = new Zend_Form_Element_Hidden('id');
        return $this->addElement($element);
    }

    public function _initIdentifier()
    {
        $element = new Zend_Form_Element_Text('identifier');
        $element->setRequired(true);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initName()
    {
        $element = new Zend_Form_Element_Text('name');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    public function _initLogoUrl()
    {
        $element = new Zend_Form_Element_Text('logoUrl');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);

    }

    public function _initClassName()
    {
        $element = new Zend_Form_Element_Select('classname');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        $element->addMultiOptions(array(
           'GROUPER' => 'Grouper',
           'OPENSOCIAL_BASIC' => 'OpenSocialBasic',
           'OPENSOCIAL_OAUTH' => 'OpenSocialOAuth'
        ));
        return $this->addElement($element);
    }

    public function _initURL()
    {
        $element = new Zend_Form_Element_Hidden('url');
        return $this->addElement($element);
    }
    
    public function _initUsername()
    {
        $element = new Zend_Form_Element_Hidden('username');
        return $this->addElement($element);
    }
    
    public function _initPassword()
    {
        $element = new Zend_Form_Element_Hidden('password');
        return $this->addElement($element);
    }
    
}