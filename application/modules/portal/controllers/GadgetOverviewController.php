<?php

class Portal_GadgetOverviewController extends Surfnet_Zend_Controller_Abstract
{

    public function showByUsageAction()
    {
        $service = new Portal_Service_Gadget();
        $results = $service->searchUsage($this->_searchParams);

        $this->view->ResultSet          = $results->getResults();
        $this->view->startIndex         = $results->getParameters()->getOffset();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }
}
