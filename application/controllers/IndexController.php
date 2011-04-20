<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        //Get the identity
        $this->view->identity = $this->_helper->Authenticate('portal');
    }

    public function indexAction()
    {
        //As default the index/index must redirect to /dashboard/index
        $this->_helper->redirector('index', 'dashboard');
    }
}

