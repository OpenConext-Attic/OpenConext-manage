<?php

class DashboardController extends Zend_Controller_Action
{
    public function init()
    {
        //Get the identity
        $this->view->identity = $this->_helper->Authenticate('portal');
    }
    
    public function indexAction()
    {
    }
}
