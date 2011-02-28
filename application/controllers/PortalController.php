<?php

require_once 'Surfnet/Filter/InArray.php';

class PortalController extends Zend_Controller_Action
{
    public function init ()
    {    
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                     ->addActionContext('gadgetavailable', 'json')
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

        $config->initialSortField = 'num';
        $config->pageSize = count($Result);
        $config->columns = array('num' => array('sort' => false, 'edit' => false),
                                 'type' => array('sort' => false, 'edit' => false),
                                );


        $this->view->config = $config;
    }
    
    public function gadgetavailableAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $options = array('filterNamespace' => 'Surfnet_Filter');
        
        /**
         * Input filtering/validation.
         * @todo Move this to the init() routine ?
         */
        $filters = array(
            'results' => array('Int'),
            'startIndex' => array('Int'),
            'dir' =>array(
                             new Surfnet_Filter_InArray(array('asc','desc'), 'asc')
                         )
        );
        
        $validators = array('*' => array());
        $validators = null;
        
        $input = new Zend_Filter_Input(
                                        $filters,
                                        $validators,
                                        $this->getRequest()->getParams(),
                                        $options
                                      );

        $gadgetList = new Model_GadgetList();
        /**
         * @todo Implement a way to get the total with a proper query.
         */
        $total = $gadgetList->getAvailable(null, null, null,0,true);

        $Result = $gadgetList->getAvailable(
                                   $input->order,
                                   $input->dir,
                                   $input->results,
                                   $input->startIndex
                               );

        $this->view->ResultSet = $Result;

        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = $input->startIndex;
        $this->view->totalRecords = $total;


        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->dir = $input->dir;
        $config->initialSortField = 'title';
        $config->startIndex = $input->startIndex;

        $config->columns = array(
                                 'title' => array(
                                     'sort' => true,
                                     'edit' => false,
                                     'formatter' => 'text'
                                  ),
                                 'author' => array(
                                     'sort' => false,
                                     'edit' => false,
                                     'formatter' => 'text'
                                  ),
                                 'screenshot' => array(
                                     'sort' => false,
                                     'edit' => false,
                                     'formatter' => 'screenshot'
                                  ),
                                 'url' => array(
                                     'sort' => false,
                                     'edit' => false,
                                     'formatter' => 'xmllink'
                                  ),
                                 'approved' => array(
                                     'sort' => false,
                                     'edit' => false,
                                     'formatter' => 'accepticon'
                                  ),
                                 'supportssso' => array(
                                     'sort' => false,
                                     'edit' => false,
                                     'formatter' => 'accepticon'
                                  ),
                                 'supports_groups' => array(
                                     'sort' => false,
                                     'edit' => false,
                                     'formatter' => 'accepticon'
                                  ),
                                );
        $this->view->config = $config;
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
        /**
         * @todo Implement a way to get the total with a proper query.
         */
        $total = $gadgetList->getUsage(null,0,true);

        $Result = $gadgetList->getUsage($limit, $offset);

        $this->view->ResultSet = $Result;
        
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = $offset;
        $this->view->totalRecords = $total;


        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/grid.ini', APPLICATION_ENV, true);

        $config->initialSortField = 'num';
        $config->startIndex = $offset;
        
        $config->pageSize = 4;
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