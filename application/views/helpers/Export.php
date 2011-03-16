<?php
class Application_View_Helper_Export extends Zend_View_Helper_Abstract
{
    public function export(Zend_Config $config)
    {
        $this->view->config = $config;
        
        return $this->view->render('export.phtml');
    }
}