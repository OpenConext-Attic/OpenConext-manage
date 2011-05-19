<?php

class Portal_GadgetDefinitionController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                                ->addActionContext('list-official', 'json')
                                ->addActionContext('list-custom', 'json')
                                ->addActionContext('save', 'json')
                                ->initContext();
    }
    
    public function listCustomAction()
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

        $service = new Portal_Service_GadgetDefinition();
        $results = $service->searchCustom($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
        $this->view->editUrl            = $this->view->url(array('action'=>'edit-custom'));
    }

    public function listOfficialAction()
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

        $service = new Portal_Service_GadgetDefinition();
        $results = $service->searchNonCustom($params);

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results->getResults();
        $this->view->recordsReturned    = $results->getResultCount();
        $this->view->totalRecords       = $results->getTotalCount();
        $this->view->addUrl             = $this->view->url(array('action'=>'add-official'));
        $this->view->editUrl            = $this->view->url(array('action'=>'edit-official'));
    }

    public function addOfficialAction()
    {
        $gadgetDefinition = new Portal_Model_GadgetDefinition();
        $gadgetDefinition->isCustom = false;
        $this->view->gadgetDefinition = $gadgetDefinition;
        $this->view->saveUrl            = $this->view->url(array('action'=>'save-official'));
        $this->render('edit');
    }

    public function editCustomAction()
    {
        $service = new Portal_Service_GadgetDefinition();
        $this->view->gadgetDefinition = $service->fetchById((int)$this->_getParam('id'));
        $this->view->saveUrl            = $this->view->url(array('action'=>'save-custom'));
        $this->render('edit');
    }

    public function editOfficialAction()
    {
        $service = new Portal_Service_GadgetDefinition();
        $this->view->gadgetDefinition   = $service->fetchById((int)$this->_getParam('id'));
        $this->view->saveUrl            = $this->view->url(array('action'=>'save-official'));
        $this->render('edit');
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = new Portal_Service_GadgetDefinition();
        return $service->delete((int)$this->_getParam('id'));
    }

    public function saveOfficialAction()
    {
        $service = new Portal_Service_GadgetDefinition();
        $gadgetDefinition = $service->save($this->_getAllParams(), true);

        if (empty($gadgetDefinition->errors)) {
            $this->_redirect($this->view->url(array('action'=>'list-official')));
        }
        else {
            $this->view->gadgetDefinition = $gadgetDefinition;
            $this->render('edit');
        }
    }

    public function saveCustomAction()
    {
        $data = $this->_getAllParams();
        $data['isCustom'] = true;
        $service = new Portal_Service_GadgetDefinition();
        $gadgetDefinition = $service->save($data, true);

        if (empty($gadgetDefinition->errors)) {
            $this->_redirect($this->view->url(array('action'=>'list-custom')));
        }
        else {
            $this->view->gadgetDefinition = $gadgetDefinition;
            $this->render('edit');
        }
    }

    public function promoteAction()
    {
        $service = new Portal_Service_GadgetDefinition();
        $gadgetDefinition = $service->promoteCustomToNonCustom((int)$this->_getParam('id'));

        if (empty($gadgetDefinition->errors)) {
            $this->_redirect($this->view->url(array('action'=>'list-custom', 'id'=>null)));
        }
        else {
            $this->view->gadgetDefinition = $gadgetDefinition;
        }
    }
}