<?php
class PortalController extends Zend_Controller_Action
{
    public function init ()
    {    
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                    ->addActionContext('gadgetcount', 'json')
                                    ->initContext();

//        $this->view->jQuery()->setLocalPath('/javascript/jquery-1.4.4.min.js');
//        $this->view->jQuery()->enable();

        $this->view->headScript()->prependFile('/javascript/flexigrid.js');
        $this->view->headLink()->prependStylesheet('/css/flexigrid.css');
        $this->view->headScript()->prependFile('http://code.jquery.com/jquery-1.4.4.min.js');
    }
    
    public function indexAction ()
    {
        
    }
    public function gadgetcountAction ()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $this->view->page = $this->getRequest()->getParam('page', 1);
        $this->view->total = 270;
        if($this->view->page == 1) {
        $this->view->rows = array(array('id' => 1,'cell' => array(1, 'Linus')),
                                  array('id' => 2,'cell' => array(2,'Rasmus')));
        }
        else {
            $this->view->rows = array();
        }
    }
    
    public function gadgetavailableAction()
    {
        
    }
    
    public function gadgetusageAction()
    {
        
    }
    
    public function invitestatusAction()
    {
        
    }
    
    public function teamtabsAction()
    {
        
    }
}