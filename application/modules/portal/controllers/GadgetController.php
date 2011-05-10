<?php

class Portal_GadgetController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();
    }

    public function listCustomAction()
    {
        
    }

    public function updateAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }
}