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
        return $this->_setupExport();
    }

    protected function _setupExport()
    {
        $config = $this->_getExportConfig();

        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();

        if (!isset($config->{$controller}) || !isset($config->{$controller}->{$action})) {
            return false; // Page not found probably
        }

        $exportConfig = $config->{$controller}->{$action};

        $exportConfig->title = "Exporteer";

        return $exportConfig;
    }

    /**
     * @return Zend_Config_Ini
     */
    protected function _getExportConfig()
    {
        return new Zend_Config_Ini(
            APPLICATION_PATH .
            self::EXPORT_CONFIG_APPLICATION_PATH,
            APPLICATION_ENV,
            true
        );
    }
}
