<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Set up the autoloaders for the default module
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initModuleAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => dirname(__FILE__)
        ));
        return $autoloader;
    }

    /**
     * Set up the db adapter
     *
     * @return Void
     */
    protected function _initDbRegistry()
    {
        $this->bootstrap('multidb');
        $multidb = $this->getPluginResource('multidb');

        Zend_Registry::set('db_coin_portal', $multidb->getDb('coin_portal'));
        Zend_Registry::set('db_engine_block', $multidb->getDb('engine_block'));

    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }

    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix('Surfnet_Helper');
    }

    protected function _initViewHelpers()
    {
        $this->bootstrap ( 'view' );
	$view = $this->getResource ( 'view' );

        $view->addHelperPath(APPLICATION_PATH . '/views/helpers/', 'Application_View_Helper');
    }
}
