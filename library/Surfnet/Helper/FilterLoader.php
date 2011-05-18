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
        return $this->_getFilterForCurrentAction();
    }

    /**
     * Load the appropriate filter.
     *
     * @return Zend_Filter_Input
     */
    protected function _getFilterForCurrentAction()
    {
        $sortOptions = $this->_getSortOptions();

        /**
         * Input filtering/validation.
         */
        $filters = array(
            'results'    => array('Int'),
            'startIndex' => array('Int'),
            'dir' =>array(
                new Surfnet_Filter_InArray(
                    array('asc', 'desc'),
                    (isset($sortOptions['defaultDir'])?$sortOptions['defaultDir']:'asc')
                )
            ),
            'sort' => array(
                new Surfnet_Filter_InArray(
                    $sortOptions['fields'],
                    $sortOptions['defaultField']
                )
            ),
        );

        $validators = null;
        $options = array(
            'filterNamespace'   => 'Surfnet_Filter',
            'allowEmpty'        => true
        );
        $requestParams = array_merge(array_flip(array_keys($filters)), $this->getRequest()->getParams());

        return new Zend_Filter_Input(
            $filters,
            $validators,
            $requestParams,
            $options
        );
    }

    /**
     *
     * @param String $controller Front controller
     * @param String $action     Action
     */
    protected function _getSortOptions()
    {
        $config = $this->_getGridConfig();

        $currentRequest = $this->getRequest();
        $module         = $currentRequest->getModuleName();
        $controller     = $currentRequest->getControllerName();
        $action         = $currentRequest->getActionName();

        if (!isset($config->$module)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown module: '$module'");
        }
        $config = $config->$module;

        if (!isset($config->$controller)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown controller: '$controller'");
        }
        $config = $config->$controller;

        if (!isset($config->$action)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown action: '$action'");
        }
        $config = $config->$action;

        $options = array(
            'defaultField'  => $config->defaultSortField,
            'fields'        => $this->_getGridSortFields($config),
        );

        if (isset($config->defaultSortDir)) {
            $options['defaultDir'] = $config->defaultSortDir;
        }

        return $options;
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
            if (isset($gridSortConfig['sortable']) && $gridSortConfig['sortable']) {
                $sortFields[] = $name;
            }
        }
        return $sortFields;
    }
}
