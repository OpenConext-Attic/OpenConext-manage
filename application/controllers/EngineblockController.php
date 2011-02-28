<?php

class EngineBlockController extends Zend_Controller_Action
{
    public function init ()
    {
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                     ->addActionContext('availableidps', 'json')
                                     ->addActionContext('availablesps', 'json')
                                     ->addActionContext('logins', 'json')
                                     ->addActionContext('idplogins', 'json')
                                     ->addActionContext('splogins', 'json')
                                     ->initContext();
    }

    /**
     * Show Available ID Providers
     *
     */
    public function availableidpsAction()
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

        $config->initialSortField = 'num';
        $config->pageSize = count($Result);
        $config->columns = array('num' => array('sort' => false, 'edit' => false),
                                 'type' => array('sort' => false, 'edit' => false),
                                );


        $this->view->config = $config;
    }

    /**
     * Show available Service Providers
     */
    public function availablespsAction()
    {

    }

    /**
     * Show ID Provider logins
     */
    public function idploginsAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $LogLogin = new Model_LogLogin();
        $Result = $LogLogin->getByIdp();

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = count($Result);
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;



        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->initialSortField = 'num';
        $config->pageSize = count($Result);
        $config->columns = array(
                                 'grouped' => array('sort' => false, 'edit' => false),
                                 'num' => array('sort' => false, 'edit' => false)
                                );

        $this->view->config = $config;
    }

    /**
     * Show Service Provider logins
     */
    public function sploginsAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $LogLogin = new Model_LogLogin();

        $total = $LogLogin->getBySP(null, null, null,0,true);
        
        $Result = $LogLogin->getBySP();

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = $total;
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;



        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->initialSortField = 'num';
        $config->pageSize = count($Result);
        $config->columns = array(
                                 'grouped' => array('sort' => false, 'edit' => false),
                                 'num' => array('sort' => false, 'edit' => false)
                                );

        $this->view->config = $config;
    }

    /**
     * Show number of logins
     */
    public function loginsAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $LogLogin = new Model_LogLogin();

        $Result = $LogLogin->getCount();

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = count($Result);
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;



        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->initialSortField = 'num';
        $config->pageSize = count($Result);
        $config->columns = array(
                                 'type' => array('sort' => false, 'edit' => false),
                                 'num' => array('sort' => false, 'edit' => false)
                                );

        $this->view->config = $config;
    }
}
