<?php



class EngineBlockController extends Zend_Controller_Action
{
    public function init ()
    {
        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                     ->addActionContext('availableidps', 'json')
                                     ->addActionContext('availablesps', 'json')
                                     ->addActionContext('idpandspcount', 'json')
                                     ->addActionContext('logins', 'json')
                                     ->addActionContext('idplogins', 'json')
                                     ->addActionContext('splogins', 'json')
                                     ->initContext();
        //Get the identity
        $this->view->identity = $this->_helper->Authenticate('portal');

        //Filter and sanitize input and set up grid.
        $input = $this->_helper->FilterLoader('portal');

        $this->view->config = $this->_helper->gridSetup($input);
        
        $this->view->exportconfig = $this->_helper->ExportSetup($input);
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

        $janusEntity = new Model_JanusEntity();
        $input = $this->_helper->FilterLoader('engineblock');
        
        $result = $janusEntity->getAvailableIdps(
            $input->sort,
            $input->dir,
            $input->results,
            $input->startIndex
        );
        $this->view->totalRecords = $janusEntity->getAvailableIdps(null, null, null, null, true);
        
        $this->view->ResultSet = $result;
        $this->view->recordsReturned = count($result);
        $this->view->startIndex = $input->startIndex;

    }

    /**
     * Show available Service Providers
     *
     */
    public function availablespsAction()
    {
        if($this->getRequest()->getParam('download', false))
        {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $janusEntity = new Model_JanusEntity();
        $input = $this->_helper->FilterLoader('engineblock');

        $result = $janusEntity->getAvailableSps(
            $input->sort,
            $input->dir,
            $input->results,
            $input->startIndex
        );
        $this->view->totalRecords = $janusEntity->getAvailableSps(null, null, null, null, true);

        $this->view->ResultSet = $result;
        $this->view->recordsReturned = count($result);
        $this->view->startIndex = $input->startIndex;
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

        $logLogin = new Model_LogLogin();

        $input = $this->_helper->FilterLoader('engineblock');

        $result = $logLogin->getByIdp(
            $input->sort,
            $input->dir,
            $input->results,
            $input->startIndex
        );
        $this->view->totalRecords = $logLogin->getCount(null, null, null, null, true);

        $this->view->ResultSet = $result;
        $this->view->recordsReturned = count($result);
        $this->view->startIndex = 0;
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

        $logLogin = new Model_LogLogin();

        $input = $this->_helper->FilterLoader('engineblock');

        $result = $logLogin->getBySP(
            $input->sort,
            $input->dir,
            $input->results,
            $input->startIndex
        );
        $this->view->totalRecords = $logLogin->getCount(null, null, null, null, true);

        $this->view->ResultSet = $result;
        $this->view->recordsReturned = count($result);
        $this->view->startIndex = 0;
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
        $input = $this->_helper->FilterLoader('engineblock');

        $LogLogin = new Model_LogLogin();

        $result = $LogLogin->getCount(
            $input->sort,
            $input->dir,
            $input->results,
            $input->startIndex
        );

        $this->view->ResultSet = $result;
        $this->view->totalRecords = $LogLogin->getCount(null,null,null,null,true);
        $this->view->recordsReturned = count($result);
        $this->view->startIndex = 0;

    }

    /**
     * 
     */
    public function idpandspcountAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            header("Content-disposition: attachment; filename=json.txt");
        }

        $janusEntity = new Model_JanusEntity();
        $input = $this->_helper->FilterLoader('engineblock');

        $result = $janusEntity->getIdpAndSpCount(
            $input->sort,
            $input->dir,
            $input->results,
            $input->startIndex
        );
        $this->view->totalRecords = $janusEntity->getIdpAndSpCount(null, null, null, null, true);

        $this->view->ResultSet = $result;
        $this->view->recordsReturned = count($result);
        $this->view->startIndex = $input->startIndex;
    }
}
