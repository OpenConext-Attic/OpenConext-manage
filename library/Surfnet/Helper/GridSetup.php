<?php

/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_GridSetup extends Zend_Controller_Action_Helper_Abstract
{
    const GRID_CONFIG_APPLICATION_PATH = '/configs/grid.ini';

    /**
     *
     * @param  string $name
     * @return Zend_Config
     */
    public function direct($name)
    {
        return $this->_getGridConfigForAction($name);
    }

    protected function _getGridConfigForAction($input)
    {
        $config = $this->_getGridConfig();

        $currentRequest = $this->getRequest();
        $module         = $currentRequest->getModuleName();
        $controller     = $currentRequest->getControllerName();
        $action         = $currentRequest->getActionName();

        if (!isset($config->$module)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown module: '$module'");
        }
        $gridConfig = $config->$module;

        if (!isset($gridConfig->$controller)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown controller: '$controller'");
        }
        $gridConfig = $gridConfig->$controller;

        if (!isset($gridConfig->$action)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get grid options, unknown action: '$action'");
        }
        $gridConfig = $gridConfig->$action;

        $gridConfig->dir        = $input->dir;
        $gridConfig->startIndex = $input->startIndex;
        $gridConfig->pageSize   = $config->pageSize;

        return $gridConfig;
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
}
