<?php

class ServiceRegistry_IdentityProviderOverviewController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch()->addActionContext('show-by-type', 'json')
                                        ->addActionContext('show-by-type', 'json-export')
                                        ->addActionContext('show-by-type', 'csv-export')
                                        ->initContext();
    }

    public function showByTypeAction()
    {
        $inputFilter = $this->_helper->FilterLoader();
        $params = Surfnet_Search_Parameters::create();
        if ($inputFilter->results) {
            $params->setLimit($inputFilter->results);
        }
        if ($inputFilter->startIndex) {
            $params->setOffset($inputFilter->startIndex);
        }
        if ($inputFilter->sort) {
            $params->setSortByField($inputFilter->sort);
        }
        if ($inputFilter->dir) {
            $params->setSortDirection($inputFilter->dir);
        }

        $service = new ServiceRegistry_Service_JanusEntity();
        $results = $service->searchIdps($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
        
    }
}