<?php

/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_ExportSetup extends Zend_Controller_Action_Helper_Abstract
{
    const EXPORT_CONFIG_APPLICATION_PATH = '/configs/export.ini';

    /**
     * @return Zend_Config
     */
    public function direct()
    {
        return $this->_getExportConfigForCurrentAction();
    }

    protected function _getExportConfigForCurrentAction()
    {
        $config = $this->_getExportConfigFile();

        $currentRequest = $this->getRequest();
        $module         = $currentRequest->getModuleName();
        $controller     = $currentRequest->getControllerName();
        $action         = $currentRequest->getActionName();

        if (!isset($config->$module)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get export options, unknown module: '$module'");
        }
        $config = $config->$module;

        if (!isset($config->$controller)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get export options, unknown controller: '$controller'");
        }
        $config = $config->$controller;

        if (!isset($config->$action)) {
            throw new Surfnet_Helper_Exception_ActionNotFound("Unable to get export options, unknown action: '$action'");
        }
        $config = $config->$action;

        return $this->_postProcessing($config);
    }

    /**
     * @return Zend_Config_Ini
     */
    protected function _getExportConfigFile()
    {
        return new Zend_Config_Ini(
            APPLICATION_PATH .
            self::EXPORT_CONFIG_APPLICATION_PATH,
            APPLICATION_ENV,
            true
        );
    }

    protected function _postProcessing($config)
    {
        $config->title = "Exporteer";
        return $config;
    }
}
