<?php

require_once 'Surfnet/Filter/InArray.php';

/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_FilterLoader extends Zend_Controller_Action_Helper_Abstract
{
   /**
     *
     * @param String $controller Front controller
     * @param String $action     Action
     */
    protected function _getSortOptions($controller, $action)
    {
        $config = new Zend_Config_Ini(
                                      APPLICATION_PATH .
                                      '/configs/grid.ini',
                                      APPLICATION_ENV,
                                      true
                                     );
        if (!isset($config->{$controller}) || !isset($config->{$controller}->{$action})) {
            return false; // Page not found probably
        }
        $options = $config->{$controller}->{$action};

        $sortOptions = array(
            'default' => $options->defaultsortfield,
        );

        $fields = array();
        foreach ($options->columns->toArray() as $name => $options) {
            if ($options['sort']) {
                $fields[] = $name;
            }
        }
        $sortOptions['fields'] = $fields;
        return $sortOptions;
    }

    /**
     * Load the appropriate filter.
     *
     * @return Zend_Filter_Input
     */
    protected function _loadFilter()
    {

        $sortOptions = $this->_getSortOptions(
                    $this->getRequest()->getControllerName(),
                    $this->getRequest()->getActionName()
                );

        /**
         * Input filtering/validation.
         */
        $filters = array(
            'results' => array('Int'),
            'startIndex' => array('Int'),
            'dir' =>array(
                             new Surfnet_Filter_InArray(
                                        array('asc', 'desc'),
                                        'desc'
                                     )
                         ),
            'sort' => array(
                             new Surfnet_Filter_InArray(
                                        $sortOptions['fields'],
                                        $sortOptions['default']
                                     )
                           ),
        );

        $validators = array('*' => array());
        $validators = null;
        $options = array(
                         'filterNamespace' => 'Surfnet_Filter',
                         'allowEmpty' => true
                        );
        return new Zend_Filter_Input(
                                      $filters,
                                      $validators,
                                      $this->getRequest()->getParams(),
                                      $options
                                    );
    }

    /**
     *
     * @param  string $name 
     * @param  array|Zend_Config $options 
     * @return Zend_Filter_Input
     */
    public function direct($name, $options=null)
    {
        return $this->_loadFilter();
    }
}
