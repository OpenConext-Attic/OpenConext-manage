<?php
/**
 * 
 * User: dennisjan
 * Date: 2/18/11
 * Time: 4:31 PM
 */
 
class Application_View_Helper_Grid extends Zend_View_Helper_Abstract
{
    public function grid(Zend_Config $config)
    {
        $this->view->columns = $config->columns;
        
        return $this->view->render('grid.phtml');
    }
}