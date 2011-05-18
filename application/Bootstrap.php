<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDataTimeZone()
    {
        date_default_timezone_set('Europe/Amsterdam');
    }

    /**
     * Set up the db adapter
     *
     * @return Void
     */
    protected function _initDbRegistry()
    {
        $this->bootstrap('multidb');
        $multiDb = $this->getPluginResource('multidb');

        Zend_Registry::set('db_coin_portal', $multiDb->getDb('coin_portal'));
        Zend_Registry::set('db_engine_block', $multiDb->getDb('engine_block'));
        Zend_Registry::set('db_service_registry', $multiDb->getDb('service_registry'));
    }

    protected function _initRegistry()
    {
        Zend_Registry::set('config', $this->getApplication()->getOptions());
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
