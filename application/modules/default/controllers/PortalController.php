<?php


class Default_PortalController extends Zend_Controller_Action
{
    public function init ()
    {
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                     ->addActionContext('gadgetavailable', 'json')
                                     ->addActionContext('gadgetcount', 'json')
                                     ->addActionContext('gadgetusage', 'json')
                                     ->addActionContext('invitestatus', 'json')
                                     ->addActionContext('teamtabs', 'json')
                                     ->initContext();

        $this->view->config = $this->_helper->gridSetup($this->_helper->FilterLoader());
    }
    
    public function indexAction ()
    {
        
    }
    
    public function gadgetcountAction ()
    {
        
    }
    
    public function gadgetavailableAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $params = $this->getRequest()->getParams();

        $gadgetList = new Model_GadgetList();

        $input = $this->_helper->FilterLoader('portal');

        $Result = $gadgetList->getAvailable(
                                   $input->sort,
                                   $input->dir,
                                   $input->results,
                                   $input->startIndex
                               );

        $this->view->ResultSet = $Result;

        $this->view->recordsReturned = count($Result);
        
        /**
         * @todo Implement a way to get the total with a proper query.
         */
        $this->view->totalRecords = $gadgetList->getAvailable(null, null, null,0,true);

    }
    
    public function gadgetusageAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }
        $input = $this->_helper->FilterLoader('portal');

        $gadgetList = new Model_GadgetList();


        $Result = $gadgetList->getUsage(
                                   $input->sort,
                                   $input->dir,
                                   $input->results,
                                   $input->startIndex
                               );

        $this->view->ResultSet = $Result;
        
        $this->view->recordsReturned = count($Result);

        $this->view->totalRecords = $gadgetList->getUsage(null, null, null,0,true);

    }
    
    public function invitestatusAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }
        $input = $this->_helper->FilterLoader('portal');

        $gadgetList = new Model_GadgetList();
        $Result = $gadgetList->getInvites(
                                           $input->sort,
                                           $input->dir,
                                           $input->results,
                                           $input->startIndex
                                         );

        $this->view->ResultSet = $Result;
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;
        $this->view->totalRecords = $gadgetList->getInvites(null, null, null,0,true);

    }
    
    public function teamtabsAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }
        $input = $this->_helper->FilterLoader('portal');
        
        $gadgetList = new Model_GadgetList();
        $Result = $gadgetList->getTeamTabs(
                                           $input->sort,
                                           $input->dir,
                                           $input->results,
                                           $input->startIndex
                                          );

        $this->view->ResultSet = $Result;
        $this->view->totalRecords = $gadgetList->getTeamTabs(null, null, null,0,true);
        $this->view->recordsReturned = count($Result);
        $this->view->startIndex = 0;

    }
}
