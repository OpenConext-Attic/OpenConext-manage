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
        return $this->_setupGrid($name);
    }

    protected function _setupGrid($input)
    {
        $config = $this->_getGridConfig();

        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();

        if (!isset($config->$controller) || !isset($config->{$controller}->$action)) {
            return false;
        }

        $gridConfig = $config->{$controller}->{$action};

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
