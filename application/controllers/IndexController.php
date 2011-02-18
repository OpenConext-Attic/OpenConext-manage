<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        //As default the index/index must redirect to /dashboard/index
        $this->_helper->redirector('index', 'dashboard');
    }
}

