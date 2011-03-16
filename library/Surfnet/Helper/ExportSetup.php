<?php
/**
 * Action helper to load standard filters.
 *
 * @author marc
 */
class Surfnet_Helper_ExportSetup extends Zend_Controller_Action_Helper_Abstract
{

    protected function _setupExport($input)
    {
        $config = new Zend_Config_Ini(
                                      APPLICATION_PATH . '/configs/export.ini',
                                      APPLICATION_ENV,
                                      true
                                     );

        /**
         * Get controller and action.
         */
        $controller = $this->getRequest()->getControllerName();
        $action = $this->getRequest()->getActionName();
        $exportConfig = $config->{$controller}->{$action};

        $exportConfig->title = "Exporteer";
        return $exportConfig;
    }

    /**
     *
     * @param  string $name 
     * @param  array|Zend_Config $options 
     * @return Zend_Config
     */
    public function direct($input)
    {
        return $this->_setupExport($input);
    }
}
