<?php
class PortalController extends Zend_Controller_Action
{
    public function init ()
    {    
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                    ->addActionContext('gadgetcount', 'json')
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

        $Result = array(
                    array('name' => 'Dennis-Jan', 'address' => 'Toutenburg 55', 'city' => 'Vlissingen', 'state' => 'Zeeland'),
                    array('name' => 'Richard', 'address' => 'Caland 35', 'city' => 'Vlissingen', 'state' => 'Zeeland'),
                    array('name' => 'bert', 'address' => 'Toutenburg 55', 'city' => 'Vlissingen', 'state' => 'Zeeland'),
                    array('name' => 'janus', 'address' => 'Caland 35', 'city' => 'Vlissingen', 'state' => 'Zeeland'),
                    array('name' => 'frits', 'address' => 'Toutenburg 55', 'city' => 'Vlissingen', 'state' => 'Zeeland'),
                    array('name' => 'klaas', 'address' => 'Caland 35', 'city' => 'Vlissingen', 'state' => 'Zeeland'));

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = 55;
        $this->view->recordsReturned = 6;
        $this->view->startIndex = 0;
        $this->view->pageSize = 25;

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->columns = array('name' => array('sort' => true, 'edit' => true),
                                'address' => array('sort' => true, 'edit' => true),
                                'city' => array('sort' => true, 'edit' => true),
                                'state' => array('sort' => true, 'edit' => true),
                                );

        $this->view->config = $config;
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