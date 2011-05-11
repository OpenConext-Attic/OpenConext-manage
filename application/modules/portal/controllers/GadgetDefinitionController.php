<?php

class Portal_GadgetDefinitionController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->identity = $this->_helper->Authenticate();

        $this->_helper->ContextSwitch->setAutoJsonSerialization(true)
                             ->addActionContext('list-official', 'json')
                             ->addActionContext('list-custom', 'json')
                             ->initContext();
    }
    
    public function listCustomAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $gadgetList = new Model_GadgetList();
        $inputFilter = $this->_helper->FilterLoader();

        $results = $gadgetList->getAllCustom(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getAllCustom(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results;
        $this->view->recordsReturned    = count($results);
        $this->view->totalRecords       = $resultTotal;
    }

    public function listOfficialAction()
    {
        if ($this->getRequest()->getParam('download', false)) {
            $this->getResponse()->setHeader('Content-disposition', 'attachment; filename=json.txt');
        }

        $gadgetList = new Model_GadgetList();
        $inputFilter = $this->_helper->FilterLoader();

        $results = $gadgetList->getAllNonCustom(
            $inputFilter->sort,
            $inputFilter->dir,
            $inputFilter->results,
            $inputFilter->startIndex
        );
        $resultTotal = $gadgetList->getAllNonCustom(
            null,
            null,
            null,
            0,
            true
        );

        $this->view->gridConfig         = $this->_helper->gridSetup($inputFilter);
        $this->view->ResultSet          = $results;
        $this->view->recordsReturned    = count($results);
        $this->view->totalRecords       = $resultTotal;
    }

    public function addAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $gadgetList = new Model_Mapper_GadgetMapper('Model_Dao_GadgetDefinition');
        echo $gadgetList->delete($gadgetList->find((int)$this->_getParam('id')));
    }

    public function saveAction()
    {
        // action body
    }

    public function updateAction()
    {
        // action body
    }
}