<?php
/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_GridSetup extends Zend_Controller_Action_Helper_Abstract
{

    protected function _setupGrid($input)
    {
        $config = new Zend_Config_Ini(
                                      APPLICATION_PATH . '/configs/grid.ini',
                                      APPLICATION_ENV,
                                      true
                                     );

        /**
         * Get controller and action.
         */
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        $gridConfig = $config->{$controller}->{$action};
        $gridConfig->dir = $input->dir;
        $gridConfig->startIndex = $input->startIndex;
        $gridConfig->pageSize = $config->pageSize;

        return $gridConfig;
    }

    /**
     *
     * @param  string $name 
     * @param  array|Zend_Config $options 
     * @return Zend_Config
     */
    public function direct($input)
    {
        return $this->_setupGrid($input);
    }
}
