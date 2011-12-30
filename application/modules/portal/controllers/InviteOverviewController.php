<?php

class Portal_InviteOverviewController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                             ->addActionContext('show-by-status', 'json')
                             ->initContext();
    }

    public function showByStatusAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $inputFilter = $this->_helper->FilterLoader();

        $params = Surfnet_Search_Parameters::create()
                ->setLimit($inputFilter->results)
                ->setOffset($inputFilter->startIndex)
                ->setSortByField($inputFilter->sort)
                ->setSortDirection($inputFilter->dir);

        $service = new Portal_Service_Invite();
        $results = $service->searchCountByStatus($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->startIndex         = $results->getParameters()->getOffset();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }
}



