<?php

class EngineBlock_LoginOverviewController extends Surfnet_Zend_Controller_Abstract
{
    public function showByTypeAction()
    {
        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountByType($this->_searchParams);
        
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    public function showByIdpAction()
    {
        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountByIdp($this->_searchParams);

        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    public function showBySpAction()
    {
        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountBySp($this->_searchParams);

        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
    }

    /**
     * Show SP logins for one IDP.
     *
     * @see BACKLOG-20
     */
    public function showSpLoginsByIdpAction()
    {
        $entityId = $this->getRequest()->getParam('eid', false);

        if (!$entityId) {
            throw new Exception('No entity ID provided!');
        }
        $this->_addExportParameter('eid', $entityId);
        $this->_searchParams->addSearchParam('entity_id', $entityId);
        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchSpLoginsByIdp($this->_searchParams);
        
        $this->view->entityId  = $entityId;
        $this->view->ResultSet = $results->getResults();
    }

    /**
     * Show IdP logins for one SP.
     *
     * @see BACKLOG-21
     */
    public function showIdpLoginsBySpAction()
    {
        $entityId = $this->getRequest()->getParam('eid', false);

        if (!$entityId) {
            throw new Exception('No entity ID provided!');
        }
        $this->_addExportParameter('eid', $entityId);
        $this->_searchParams->addSearchParam('entity_id', $entityId);
        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchIdpLoginsBySp($this->_searchParams);
        $this->view->entityId  = $entityId;
        $this->view->ResultSet = $results->getResults();
    }
}
