<?php

require_once 'Surfnet/Filter/InArray.php';

/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_FilterLoader extends Zend_Controller_Action_Helper_Abstract
{
    const GRID_CONFIG_APPLICATION_PATH = '/configs/grid.ini';

    /**
     *
     * @return Zend_Filter_Input
     */
    public function direct()
    {
        return $this->_loadFilter();
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

        if ($sortOptions === false) {
            return false;
        }

        /**
         * Input filtering/validation.
         */
        $filters = array(
            'results'    => array('Int'),
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

        $validators = null;
        $options = array(
            'filterNamespace'   => 'Surfnet_Filter',
            'allowEmpty'        => true
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
     * @param String $controller Front controller
     * @param String $action     Action
     */
    protected function _getSortOptions($controller, $action)
    {
        $config = $this->_getGridConfig();

        if (!isset($config->{$controller}) || !isset($config->{$controller}->{$action})) {
            return false; // Page not found probably
        }

        $gridSortConfig = $config->{$controller}->{$action};

        return array(
            'default' => $gridSortConfig->defaultsortfield,
            'fields'  => $this->_getGridSortFields($gridSortConfig),
        );
    }

    /**
     * @return Zend_Config_Ini
     */
    protected function _getGridConfig()
    {
        return new Zend_Config_Ini(
            APPLICATION_PATH .
            self::GRID_CONFIG_APPLICATION_PATH,
            APPLICATION_ENV,
            true
        );
    }

    protected function _getGridSortFields(Zend_Config $gridSortConfig)
    {
        $sortFields = array();
        foreach ($gridSortConfig->columns->toArray() as $name => $gridSortConfig) {
            if ($gridSortConfig['sort']) {
                $sortFields[] = $name;
            }
        }
        return $sortFields;
    }
}
