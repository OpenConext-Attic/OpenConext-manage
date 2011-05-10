<?php

class Application_View_Helper_Grid extends Zend_View_Helper_Abstract
{
    public function grid($config)
    {
        if (!$config) {
            return false;
        }

        return $this->view->partial('grid.phtml', 'default', array('config'=>$config));
    }
}