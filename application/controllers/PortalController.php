<?php
class PortalController extends Zend_Controller_Action
{
    public function init ()
    {    
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                    ->addActionContext('gadgetcount', 'json')
                                    ->addActionContext('gadgetusage', 'json')
                                    ->initContext();
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

        $gadgetList = new Model_GadgetList();
        $Result = $gadgetList->getCount();

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = count($Result);
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;
        
        
        
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->initialSortField = 'type';
        $config->pageSize = count($Result);
        $config->columns = array('type' => array('sort' => false, 'edit' => false),
                                 'num' => array('sort' => false, 'edit' => false),
                                );


        $this->view->config = $config;
    }
    
    public function gadgetavailableAction()
    {
    }
    
    public function gadgetusageAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $limit = intval($this->getRequest()->getParam('results'));
        $offset = intval($this->getRequest()->getParam('startIndex'));

        $gadgetList = new Model_GadgetList();
        $Result = $gadgetList->getUsage($limit, $offset);

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = count($Result);
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;



        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->initialSortField = 'num';
        $config->pageSize = 25;
        $config->columns = array('num' => array('sort' => false, 'edit' => false),
                                 'title' => array('sort' => false, 'edit' => false),
                                 'author' => array('sort' => false, 'edit' => false),
                                );


        $this->view->config = $config;
    }
    
    public function invitestatusAction()
    {
        
    }
    
    public function teamtabsAction()
    {
        
    }
}