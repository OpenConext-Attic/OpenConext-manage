<?php

class Application_View_Helper_Export extends Zend_View_Helper_Abstract
{
    public function export($config)
    {
        if (!$config) {
            return false;
        }

        return $this->view->partial('export.phtml', 'default', array('config'=>$config));
    }
}