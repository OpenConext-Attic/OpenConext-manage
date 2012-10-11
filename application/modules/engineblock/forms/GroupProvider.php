<?php
/**
 *
 */

class EngineBlock_Form_GroupProvider extends Zend_Form
{
    /**
     * 
     */
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

    /**
     * @return EngineBlock_Form_GroupProvider
     */
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

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initIdentifier()
    {
        $element = new Zend_Form_Element_Text('identifier');
        $element->setRequired(true);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initName()
    {
        $element = new Zend_Form_Element_Text('name');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initLogoUrl()
    {
        $element = new Zend_Form_Element_Text('logoUrl');
        $element->setRequired(TRUE);
        $element->setAllowEmpty(false);
        return $this->addElement($element);

    }

    /**
     * @return EngineBlock_Form_GroupProvider
     */
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

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initURL()
    {
        $element = new Zend_Form_Element_Hidden('url');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initUsername()
    {
        $element = new Zend_Form_Element_Hidden('username');
        return $this->addElement($element);
    }

    /**
     * @return EngineBlock_Form_GroupProvider
     */
    public function _initPassword()
    {
        $element = new Zend_Form_Element_Hidden('password');
        return $this->addElement($element);
    }

    /**
     * Validate regular expression config values, sets errors
     * member on $gp when errors are found
     *
     * Note: these inputs are not rendered with Zend_Form
     *
     * @param array $data
     * @return array errors
     */
    public function validateCustomFields(array $data)
    {
        $errors = array();

        $errors['user_id_match_regex'] = $this->_validateRegularExpressionField(
            $data, 'user_id_match', 'user_id_match_search'
        );

        $errors['modify_group_id_search'] = $this->_validateRegularExpressionField(
            $data, 'modify_group_id', 'modify_group_id_search'
        );

        $errors['modify_user_id_search'] = $this->_validateRegularExpressionField(
            $data, 'modify_user_id', 'modify_user_id_search'
        );

        $errors['modify_group_rules'] = $this->_validateRegularExpressionSetField(
            $data, 'modify_group', 'modify_group_rule'
        );

        $errors['modify_user_rules'] = $this->_validateRegularExpressionSetField(
            $data, 'modify_user', 'modify_user_rule'
        );

        // remove empty arrays
        $filtered = array();
        foreach ($errors as $key => $value) {
            if (!empty($value)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }


    /**
     * Validate a regular expression+checkbox field
     *
     * @param array $data
     * @param string $conditionField
     * @param string $queryField
     * @return string array message
     */
    protected function _validateRegularExpressionField(array $data, $conditionField, $queryField)
    {
        // see if this field is enabled by checkbox
        if (isset($data[$conditionField]) && ($data[$conditionField] === 'on')) {
            if (empty($data[$queryField])) {
                return array('Please enter a search value');
            } else if (!$this->_validateRegularExpression($data[$queryField])) {
                return array(sprintf(
                    'The search value \'%s\' is not a valid regular expression',
                    $data[$queryField]
                ));
            }
        }

        return array();
    }

    /**
     * Validate the fields where a set of regular expressions are input
     *
     * @param array $data
     * @param string $conditionField
     * @param string $queryField
     * @return array error messages
     */
    protected function _validateRegularExpressionSetField(array $data, $conditionField, $queryField)
    {
        // can return multiple errors
        $errors = array();

        // user replace
        if (isset($data[$conditionField]) && ($data[$conditionField] === 'on')) {
            if (empty($data[$queryField])) {
                return array('Please enter a search value');
            } else {
                foreach ($data[$queryField] as $rule) {
                    if (empty($rule['search'])) {
                        return array('Please enter a search value'); // only return first error
                    } else if (!$this->_validateRegularExpression($rule['search'])) {
                        $errors[] = sprintf(
                            'The search value \'%s\' is not a valid regular expression',
                            $rule['search']
                        );
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Returns wether the provided regular expression is valid
     *
     * @param type $regex
     */
    protected function _validateRegularExpression($regex)
    {
        return (@preg_replace($regex, '', '') !== null);
    }
}