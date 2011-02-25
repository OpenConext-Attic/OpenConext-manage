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
    protected function _initDbAdapter()
    {
        $this->bootstrap('db');
        $db = $this->getResource('db');
        if ($db != null) {
            Zend_Registry::set('db', $db);
        } else {
            throw new Exception('Cannot create database adapter');
        }
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }

    protected function _initJQuery()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    protected function _initViewHelpers() {
        $this->bootstrap ( 'view' );
	$view = $this->getResource ( 'view' );

        $view->addHelperPath(APPLICATION_PATH . '/views/helpers/', 'Application_View_Helper');
    }
}
